<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmployeeApproverController;
use App\Mail\EmployeeCoeMail;
use App\EmployeeCoe;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use App\ApproverSetting;

class EmployeeCoeController extends Controller {
    public function publicCoe() {

        if (Auth::check()) {
            return redirect('/coe-request');
        }

        return view('forms.coerequest.public_coe_request');
    }

    public function coe(Request $request) {
        $today = date('Y-m-d');
        $from = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $to = $request->to ?? date('Y-m-d');
        $status = $request->status ?? '';

        $get_approvers = new EmployeeApproverController;

        $coes = EmployeeCoe::with('user')
            ->where('user_id', auth()->user()->id)
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->whereDate('applied_date', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderByRaw("FIELD(status, 'Approved', 'Pending', 'Declined') ASC")
            ->orderBy('created_at', 'DESC')
            ->get();

        $coes_all = EmployeeCoe::with('user')
            ->where('user_id', auth()->user()->id)
            ->get();

        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);

        $coe_approvers = ApproverSetting::with('user.employee')
            ->where('type_of_form', 'coe')
            ->where('status', 'Active')
            ->get();

        $getApproverForEmployee = function ($employee) use ($coe_approvers) {
            return $this->getApproverForEmployee($employee, $coe_approvers);
        };

        return view('forms.coerequest.coerequest', [
            'header' => 'forms',
            'all_approvers' => $all_approvers,
            'coes' => $coes,
            'coes_all' => $coes_all,
            'from' => $from,
            'to' => $to,
            'status' => $status,
            'coe_approvers' => $coe_approvers,
            'getApproverForEmployee' => $getApproverForEmployee,
        ]);
    }

    // heads-up alert 
    public function notifyHr() {
        $cutoff = now()->subDays(5);
        $notified = 0;

        $pendingCoes = EmployeeCoe::with(['user.employee'])
            ->where('status', 'Pending', 'Processing')
            ->whereDate('created_at', '<=', $cutoff)
            ->get();

        $coe_approvers = ApproverSetting::with('user')
            ->where('type_of_form', 'coe')
            ->where('status', 'Active')
            ->get();

        foreach ($pendingCoes as $coe) {
            $employee = $coe->user->employee ?? null;
            if ($employee) {
                $approver = $this->getApproverForEmployee($employee, $coe_approvers);
            } else {
                $approver = $coe_approvers->first();
            }

            if ($approver && $approver->user->email) {

                // skip if approver is HR Head
                if ($approver->user->employee && $approver->user->employee->position === 'HR Head') {
                    continue;
                }

                $data = [
                    'coe_id' => $coe->id,
                    'coe_reference' => $coe->reference_number,
                    'name' => $coe->first_name . ' ' . $coe->last_name,
                    'purpose' => $coe->purpose,
                    'reason_for_request' => $coe->reason_for_request,
                    'created_at' => $coe->created_at,
                    'due_date' => $coe->created_at->copy()->addDays(5),
                    'days_pending' => now()->diffInDays($coe->created_at),
                ];


                Mail::to($approver->user->email)->send(new \App\Mail\CoeReminderMail($data));
                $notified++;
            }
        }

        return "Notified {$notified} approver(s) about pending COE requests.";
    }

    public function store(Request $request) {
        // general rules
        $rules = [
            'reason_for_request' => 'required|string|in:Plain,With Salary',
            'purpose' => 'required|string|in:Employment,Visa Application,Bank,Employment,Other',
            'purpose_other' => 'nullable|string|max:500',
            'purpose_visa' => 'nullable|string|max:255',
            'purpose_bank' => 'nullable|string|max:255',
            'receive_method' => 'required|string|in:Email,Viber,Hard Copy',
            'additional_notes' => 'nullable|string|max:1000',
        ];

        // requires when submitting from public
        if (!Auth::check()) {
            $rules['purpose'] = 'required|string|in:Employment,Other';
            $rules['first_name'] = 'required|string|max:255';
            $rules['last_name'] = 'required|string|max:255';
            $rules['hire_date'] = 'required|date|before_or_equal:today';
            $rules['resign_date'] = 'required|date|before_or_equal:today|after_or_equal:hire_date';
            $rules['email'] = 'required_if:receive_method,Email,Hard Copy|nullable|email|max:255';
            $rules['designation'] = 'required|string|max:255';
            $rules['viber_number'] = 'required_if:receive_method,Viber|nullable|digits:11';
            $rules['gender'] = 'required|string|in:Male,Female';
        }

        $request->validate($rules);

        $coe = new EmployeeCoe;

        // generating reference number
        $latestCoe = EmployeeCoe::orderBy('id', 'desc')->first();
        if ($latestCoe && $latestCoe->reference_number) {
            $number = intval(substr($latestCoe->reference_number, 4)) + 1;
        } else {
            $number = 1;
        }

        $coe->reference_number = 'COE-' . str_pad($number, 5, '0', STR_PAD_LEFT);

        if (Auth::check() && !$request->is('public/coe-request*')) {
            $user = Auth::user();
            $employee = $user->employee;
            $coe->user_id = $user->id;
            $coe->schedule_id = $employee->schedule_id ?? null;
            $coe->hiring_date = $employee->original_date_hired;
            $coe->first_name = $employee->first_name;
            $coe->last_name = $employee->last_name;
            $coe->email = $request->email; // dynamic: it could be default or from user input
            $coe->employment_status = 'Active';
            $coe->created_by = $user->id;
        } else {
            $coe->first_name = $request->first_name;
            $coe->last_name = $request->last_name;
            $coe->employment_status = 'Separated';
            $coe->hiring_date = $request->hire_date;
            $coe->resign_date = $request->resign_date;
            $coe->email = $request->email;
            $coe->gender = $request->gender;
        }

        $coe->reason_for_request = $request->reason_for_request;
        $coe->designation = Auth::check() ? $employee->position : $request->designation;
        $detail = null;

        switch($request->purpose) {
            case 'Visa Application': $detail = $request->purpose_visa; break;
            case 'Bank': $detail = $request->purpose_bank; break;
            case 'Other': $detail = $request->purpose_other; break;
        }

        $coe->purpose = $detail ? $request->purpose . ' - ' . $detail : $request->purpose;
        $coe->receive_method = $request->receive_method;
        if ($request->receive_method === 'Viber') {
            $coe->viber_number = $request->viber_number ?: optional(Auth::user()->employee)->personal_number;
        }
        $coe->additional_notes = $request->additional_notes;
        $coe->applied_date = now();
        $coe->status = 'Pending';

        try {
            $coe->save();

            $data = [
                'coe_id' => $coe->id,
                'name' => Auth::check() ? Auth::user()->name : $request->first_name . ' ' . $request->last_name,
                'reason_for_request' => $request->reason_for_request,
                'employment_status' => $coe->employment_status,
                'purpose' => $coe->purpose,
                'designation' => Auth::check() ? $employee->position : $request->designation,
                'hire_date' => Auth::check() && Auth::user()->employee ? Auth::user()->employee->original_date_hired : $request->hire_date,
                'receive_method' => $request->receive_method,
                'email' => Auth::check() && Auth::user()->employee ? Auth::user()->employee->personal_email : $request->email,
                'viber_number' => $coe->viber_number,
                'personal_number' => Auth::check() && Auth::user()->employee ? Auth::user()->employee->personal_number : null,
                'additional_notes' => $request->additional_notes,
                'resignation_date' => Auth::check() && Auth::user()->employee ? optional(Auth::user()->employee)->date_resigned : null,
                'coe_reference' => $coe->reference_number,
            ];

            $coe_approvers = ApproverSetting::with('user.employee')
                ->where('type_of_form', 'coe')
                ->where('status', 'Active')
                ->get();

            $approverEmails = $coe_approvers
                ->pluck('user.email')
                ->filter()
                ->unique()
                ->values();

            try {
                // send to all approvers
                 foreach ($approverEmails as $approverEmail) {
                     \Log::info('COE notification to approver: ' . $approverEmail);
                     Mail::to($approverEmail)->send(new EmployeeCoeMail($data));
                 }

                 // confirmation to requestor
                 if ($data['email']) { 
                     \Log::info('COE confirmation to requestor: '. $data['email']); 
                     Mail::to($data['email'])->send((new EmployeeCoeMail($data))->forRequestor()); 
                 } 
            } catch (\Exception $e) {
                \Log::warning('COE email failed but request saved: ' . $e->getMessage());
            }
            
            Alert::success('COE request submitted successfully')->persistent('Dismiss');
        } catch (\Exception $e) {
            Alert::error('Failed to submit COE request: ' . $e->getMessage())->persistent('Dismiss');
        }

        return back();
    }

    public function edit(Request $request, $id) {
        $request->validate([
            'email' => 'required_if:receive_method,Email,Hard Copy',
            'reason_for_request' => 'required|string|in:Plain,With Salary',
            'purpose' => 'required|string|in:Visa Application,Bank,Other',
            'purpose_visa' => 'nullable|string|max:255',
            'purpose_bank' => 'nullable|string|max:255',
            'purpose_other' => 'nullable|string|max:500',
            'receive_method' => 'required|string|in:Email,Viber,Hard Copy',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        $coe = EmployeeCoe::findOrFail($id);
        if ($coe->user_id !== Auth::id()) {
            Alert::error('Unauthorized action')->persistent('Dismiss');
            return back();
        }

        if ($coe->status === 'Approved') {
            Alert::warning('Action cannot be done')->persistent('Dismiss');
            return back();
        }

        $coe->reason_for_request = $request->reason_for_request;
        $coe->designation = Auth::user()->employee->position;

        $detail = null;
        switch($request->purpose) {
            case 'Visa Application': $detail = $request->purpose_visa; break;
            case 'Bank': $detail = $request->purpose_bank; break;
            case 'Other': $detail = $request->purpose_other; break;
        }

        $coe->purpose = $detail ? $request->purpose . ' - ' . $detail : $request->purpose;
        $coe->receive_method = $request->receive_method;
        $coe->email = $request->email;

        if ($request->receive_method === 'Viber') {
            $coe->viber_number = $request->viber_number ?: optional(Auth::user()->employee)->personal_number;
        }

        $coe->additional_notes = $request->additional_notes;
        $coe->updated_at = now();

        $coe->save();

        Alert::success('COE request updated successfully')->persistent('Dismiss');
        return back();
    }

    public function cancel($id) {
        $coe = EmployeeCoe::findOrFail($id);

        // anti IDOR: wag ka nang tumesting
        if ($coe->user_id !== Auth::id()) {
            Alert::error('Unauthorized action')->persistent('Dismiss');
            return back();
        }

        // guard processing and approved request from being cancelled
        if (in_array($coe->status, ['Processing', 'Approved'])) {
            Alert::error('Request cannot be cancel. It is already ' . $coe->status . '.')->persistent('Dismiss');
            return back();
        }

        $coe->status = 'Cancelled';
        $coe->save();

        $this->notifyApproverCoeCancelled($coe);

        Alert::success('COE Request has been cancelled.')->persistent('Dismiss');
        return back();
    }

    public function notifyApproverCoeCancelled($coe) {
        $coe_approvers = \App\ApproverSetting::with('user')
            ->where('type_of_form', 'coe')
            ->where('status', 'Active')
            ->get();

        if ($coe->user_id) {
            $employee = $coe->user->employee ?? null;
            $approver = $employee
                ? $this->getApproverForEmployee($employee, $coe_approvers)
                : $coe_approvers->first();
        } else {
            $approver = $coe_approvers->first();
        }

        if ($approver && $approver->user->email) {
            $data = [
                'coe_id' => $coe->id,
                'coe_reference' => $coe->reference_number,
                'name' => $coe->first_name . ' ' . $coe->last_name,
                'purpose' => $coe->purpose,
                'reason_for_request' => $coe->reason_for_request,
                'status' => $coe->status,
            ];

            if ($approver->user->employee && $approver->user->employee->position === 'HR Head') {
                return; // or just don't send
            }

            Mail::to($approver->user->email)->send(new \App\Mail\CancelledCoeMail($data));
        }
    }

    public function printCoe($id) {
        $coe = EmployeeCoe::findOrFail($id);

        $coe_approvers = \App\ApproverSetting::with('user')
            ->where('type_of_form', 'coe')
            ->where('status', 'Active')
            ->get();

        $coordinatorIds = $coe_approvers->pluck('user_id')->toArray();

        if (!in_array(auth()->user()->id, $coordinatorIds)) {
            Alert::error('Unauthorized. You do not have access to this resource')->persistent('Dismiss');
            return back();
        };

        return view('for-approval.coe_certificate', compact('coe'));
    }

    private function getApproverForEmployee($employee, $coe_approvers) {
        $employee_company = $employee->company_code ?? $employee->company_id ?? null;

        if ($employee_company) {
            foreach ($coe_approvers as $approver) {
                $approver_company = $approver->user->employee->company_code ?? $approver->user->employee->company_id ?? null;
                if ($approver_company == $employee_company) {
                    return $approver;
                }
            }
        }

        return $coe_approvers->first();
    }
}
