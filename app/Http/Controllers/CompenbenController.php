<?php

namespace App\Http\Controllers;

use App\Compenben;
use App\Employee;
use App\Mail\CompenbenSubmittedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class CompenbenController extends Controller {
    const ISSUE_CATEGORIES = [
        'Gross or Net Pay',
        'Time Keeping',
        'Leave',
        'Deduction in Payroll',
        'SSS/PHIC/HDMF Contributions',
        'SSS/HDMF Loans',
        'Metrobank Pay Card',
        'Incentive',
        'Payslip',
        'Form Request - Historical Payslip',
        'Form Request - Pag-Ibig or HDMF Loan Form',
        'Form Request - SSS Contribution Certification',
        'Form Request - PHIC Contribution Certification',
        'Form Request - Pag-Ibig or HDMF Contribution Certification',
        'Others',
    ];

    private function authorizeHrHead() {
        $employee = optional(auth()->user())->employee;

        if (
            !$employee
            || $employee->position !== 'HR Head'
            || $employee->status !== 'Active'
        ) {
            abort(403);
        }
    }

    public function index(Request $request) {
        $this->authorizeHrHead();

        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);

        $query = Compenben::query()->orderBy('created_at', 'desc');

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return view('compenben.index', [
            'requests' => $query->get(),
            'from' => $request->from,
            'to' => $request->to,
            'header' => 'reports',
        ]);
    }

    public function show($id) {
        $this->authorizeHrHead();

        return view('compenben.show', [
            'compenben' => Compenben::findOrFail($id),
            'header' => 'reports',
        ]);
    }

    public function create() {
        $user = auth()->user();
        $employee = $user ? $user->employee : null;

        if (!$employee) {
            abort(403);
        }

        return view('compenben.create', [
            'issueCategories' => self::ISSUE_CATEGORIES,
            'employeeData' => [
                'first' => $employee->first_name,
                'last' => $employee->last_name,
                'email' => $user->email,
                'phone' => $employee->personal_number,
                'designation' => $employee->position,
                'work_location' => $employee->location,
            ],
            'header' => 'forms',
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'first' => 'required|string|max:255',
            'last' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'string', 'regex:/^09[0-9]{9}$/'],
            'designation' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'issue' => [
                'required',
                'string',
                Rule::in(self::ISSUE_CATEGORIES),
            ],
            'other_issue' => 'required_if:issue,Others|string|max:240',
            'details' => 'required|string|max:500',
        ], [
            'other_issue.required_if' => 'Please specify the issue category.',
        ]);

        $user = auth()->user();

        if (!$user || !$user->employee) {
            abort(403);
        }

        if ($validated['issue'] === 'Others') {
            $validated['issue'] = 'Others - ' . trim($validated['other_issue']);
        }

        unset($validated['other_issue']);

        $compenben = DB::transaction(function () use ($validated, $user) {
            $compenben = new Compenben($validated);
            $compenben->user_id = $user->id;
            $compenben->employee_code = $user->employee->employee_code;
            $compenben->save();

            $compenben->reference_no = 'CB-' . str_pad(
                $compenben->id,
                6,
                '0',
                STR_PAD_LEFT
            );
            $compenben->save();

            return $compenben;
        });

        $hrHeadEmails = Employee::with('user')
            ->where('position', 'HR Head')
            ->where('status', 'Active')
            ->whereHas('user', function ($query) {
                $query->where('status', 'Active')
                    ->whereNotNull('email')
                    ->where('email', '!=', '');
            })
            ->get()
            ->pluck('user.email')
            ->filter(function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })
            ->unique()
            ->values()
            ->all();

        if (!empty($hrHeadEmails)) {
            try {
                Mail::to($hrHeadEmails)->send(
                    new CompenbenSubmittedMail($compenben)
                );
            } catch (\Throwable $exception) {
                \Log::warning(
                    'CompenBen HR Head notification failed for record '
                    . $compenben->reference_no
                    . ': '
                    . $exception->getMessage()
                );
            }
        } else {
            \Log::warning(
                'CompenBen record '
                . $compenben->reference_no
                . ' was submitted but no active HR Head work email was found.'
            );
        }

        Alert::success('Form submitted successfully.')->persistent('Dismiss');

        return redirect()->back();
    }
}
