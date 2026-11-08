<?php

namespace App\Http\Controllers;

use AddBmcAccountabilityReturns;
use App\Department;
use App\Employee;
use App\EmployeeDocument;
use App\ExitInterview;
use App\ExitInterviewInvitation;
use App\Mail\ExitInterviewConfirmationMail;
use App\Mail\ExitInterviewInvitationMail;
use App\Mail\ExitInterviewSubmittedMail;
use App\Services\ExitInterview\ExitInterviewReferenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use RealRashid\SweetAlert\Facades\Alert;

class ExitInterviewController extends Controller {
    public function index(Request $request) {
        // spp, matt, sharon,  macie, rem
        // nate and matt should filter records with position under BDD
        // if under BDD nate and matt can see those records
 
        /* just add here the positions that are applicable for this constraint */
        $general = [
            'President',
            'Human Resource Assistant',
            'Recruitment Supervisor', 
            'HR Head',
            'Organizational Development - HR Assistant',
            'Audit Head / PQD Head',
        ];

        $hra = 'Organizational Development - HR Assistant';

        $bdd = [
            'VP - Marketing and Branding',
            'VP - Business Development'
        ];

        $employee = auth()->user()->employee;
        $isAuthorized = $employee 
            && $employee->status === 'Active'
            && in_array($employee->position, array_merge($general, $bdd), true);

        $from = $request->input('from');
        $to = $request->input('to');

        if ($isAuthorized) {
            $query = ExitInterview::with('employee.user');

            if ($from && $to) {
                $query->whereBetween('submitted_at', [$from, $to . ' 23:59:59']);
            }

            // filter lists to matt and nate
            if ($employee && in_array($employee->position, $bdd, true)) {
                $query->where(function ($exitInterviewQuery) {
                    $exitInterviewQuery->whereHas('employee', function ($employeeQuery) {
                        $employeeQuery->where('department_id', 3);
                    })->orWhereIn('department', [
                        'Business Development Department',
                        'BDD',
                    ]);
                });
            }

            if ($employee && $employee->position === $hra) {
                $query->whereDoesntHave('employee', function ($employeeQuery) {
                    $employeeQuery->where('company_id', 2);
                });
            }

            $exit_interviews = $query
                ->orderBy('submitted_at', 'DESC')
                ->get();
        } else {
            $exit_interviews = ExitInterview::with('employee.user')
                ->where('employee_id', $employee->id)
                ->orderBy('submitted_at', 'DESC')
                ->get();
        }

        $awaitingResponseCount = 0;

        if ($employee && $employee->position === 'HR Head') {
            $awaitingResponseCount = ExitInterviewInvitation::whereNotNull('sent_at')
                ->whereNull('completed_at')
                ->whereNull('delivery_error')
                ->count();

        }

        $header = 'exit_interviews';
        return view('forms.exit_interviews.index', compact(
            'header',
            'exit_interviews',
            'from',
            'to',
            'awaitingResponseCount'
        ));
    }

    // for public submission
    public function updateDates(Request $request, $id) {
        $interview = ExitInterview::findOrFail($id);
        $employee = auth()->user()->employee;

        $canEdit = $employee 
            && $employee->status === 'Active'
            && in_array($employee->position, [
                'HR Head',
                'Organizational Development - HR Assistant',
                'Audit Head / PQD Head',
            ], true);

        if (!$canEdit || $interview->submission_source !== 'public') {
            abort(403);
        }

        $data = $request->validate([
            'hire_date' => 'required|date|before_or_equal:today',
            'separation_date' => 'required|date|after_or_equal:hire_date',
            'job_title' => 'required|string|max:255'
        ]);

        $interview->update($data);

        Alert::success('Details updated successfully.')->persistent('Dismiss');

        return back();
    }

    public function sendInvitation(Request $request) {
        $sender = optional(auth()->user())->employee;

        if (!$sender || $sender->position !== 'HR Head') {
            abort(403);
        }

        $request->validate([
            'recipient_email' => 'required|email|max:255',
        ]);

        $recipientEmail = strtolower(trim((string) $request->recipient_email));
        $employees = Employee::with('user')
            ->whereDoesntHave('exitInterviews')
            ->whereNotNull('first_name')
            ->whereNotNull('last_name')
            ->where(function ($query) use ($recipientEmail) {
                $query->whereRaw('LOWER(TRIM(personal_email)) = ?', [$recipientEmail])
                    ->orWhereHas('user', function ($userQuery) use ($recipientEmail) {
                        $userQuery->whereRaw('LOWER(TRIM(email)) = ?', [$recipientEmail]);
                    });
            })
            ->get();

        if ($employees->count() !== 1) {
            Alert::error(
                $employees->isEmpty()
                    ? 'No eligible employee was found with that email address. Use the employee’s registered email.'
                    : 'More than one employee matches that email address. Please contact support.'
            )->persistent('Dismiss');

            return redirect()->back()->withInput();
        }

        $employee = $employees->first();

        $invitation = ExitInterviewInvitation::create([
            'employee_number' => trim((string) $employee->employee_number) ?: null,
            'first_name' => trim((string) $employee->first_name),
            'last_name' => trim((string) $employee->last_name),
            'recipient_email' => $recipientEmail,
            'sent_by' => auth()->id(),
        ]);

        try {
            Mail::to($invitation->recipient_email)->send(
                new ExitInterviewInvitationMail(
                    $invitation,
                    route('exit-interview.public.create')
                )
            );

            $invitation->update([
                'sent_at' => now(),
                'delivery_error' => null,
            ]);
        } catch (\Throwable $exception) {
            $invitation->update([
                'delivery_error' => $exception->getMessage(),
            ]);

            \Log::error('Exit interview invitation email failed: ' . $exception->getMessage());

            Alert::error('The invitation could not be sent. Please try again.')->persistent('Dismiss');
            return redirect()->back()->withInput();
        }

        Alert::success('Exit interview form link sent successfully.')->persistent('Dismiss');
        return redirect()->back();
    }

    // internal
    public function create() {
        $employee = auth()->user()->employee;

        $hasSeparationLetter = EmployeeDocument::where('employee_id', $employee->id)
            ->where('document_type', '17')
            ->whereNull('retracted_at')
            ->exists();

        if (!$hasSeparationLetter) {
            Alert::error("You are not eligible to access this form. Please contact HR")->persistent('Dismiss');
            return redirect()->back();
        }

        $existingInterview = ExitInterview::where(
            'employee_number',
            $employee->employee_number
        )->first();

        if ($existingInterview) {
            Alert::info('You have already submitted your exit interview')->persistent('Dismiss');
            return redirect('exit-interviews/'.$existingInterview->id);
        }

        $header = 'exit_interviews';
        return view('forms.exit_interviews.create', compact('header'));
    }

    public function publicCreate() {
        $departments = Department::where('status', 1)
            ->orderBy('name')
            ->get(['name']);

        return view('forms.exit_interviews.public_create', compact('departments'));
    }

    public function publicStore(
        Request $request,
        ExitInterviewReferenceService $references
    ) {
        $request->validate([
            'employee_number'             => 'nullable|string|max:50',
            'first_name'                  => 'required|string|max:100',
            'last_name'                   => 'required|string|max:100',
            'email'                       => 'required|email|max:255',
            'job_title'                   => 'required|string|max:150',
            'department'                  => 'required|string|max:150|exists:departments,name,status,1',
            'direct_supervisor_name'      => 'required|string|max:255',
            'work_location'               => 'required|string|max:150',
            'hire_date'                   => 'required|date|before_or_equal:today',
            'separation_date'             => 'required|date|after_or_equal:hire_date',
            'is_separation_voluntary'     => 'required|boolean',
            'separation_type'             => [
                'required_if:is_separation_voluntary,0',
                'nullable',
                'string',
                'in:Retrenchment,Redundancy,End of Probationary Period due to Performance Gap,Termination due to Just Cause',
            ],
            'separation_comment'          => 'required_if:is_separation_voluntary,0|nullable|string|max:1000',
            'is_leaving_for_new_job'      => 'required_if:is_separation_voluntary,1|nullable|boolean',
            'reason_of_leave'             => 'required_if:is_separation_voluntary,1|nullable|array|min:1',
            'reason_of_leave.*'           => 'string|max:255',
            'reason_of_leave_other'       => 'nullable|string|max:255',
            'career_type_importance'      => 'required|array|min:1',
            'career_type_importance_other' => 'nullable|string|max:255',
            'new_job_offer_text'          => 'required_if:is_leaving_for_new_job,1|nullable|string|max:255',
            'rating_job_responsibilities' => 'required|integer|min:1|max:5',
            'rating_personal_goals'       => 'required|integer|min:1|max:5',
            'rating_work_environment'     => 'required|integer|min:1|max:5',
            'rating_immediate_superior'   => 'required|integer|min:1|max:5',
            'rating_salary'               => 'required|integer|min:1|max:5',
            'rating_benefits'             => 'required|integer|min:1|max:5',
            'most_liked_text'             => 'required|string',
            'least_liked_text'            => 'required|string',
            'understands_company_goals'   => 'required|boolean',
            'company_lives_core_values'   => 'required|boolean',
            'goals_explanation'           => 'required_if:understands_company_goals,0|nullable|string|max:255',
            'core_values_explanation'     => 'required_if:company_lives_core_values,0|nullable|string|max:255',
            'frequently_performance_feedback' => 'required|string',
            'suggestion_company'          => 'nullable|string',
            'suggestion_department'       => 'nullable|string',
            'suggestion_other_employees'  => 'nullable|string',
            'other_feedback_text'         => 'nullable|string',
        ]);

        $employeeNumber = trim((string) $request->employee_number);
        $employee = null;

        if ($employeeNumber !== '') {
            $existingInterview = ExitInterview::where(
                'employee_number',
                $employeeNumber
            )->first();

            if ($existingInterview) {
                Alert::warning(
                    'You have already submitted an exit interview'
                )->persistent('Dismiss');

                return redirect()->back()->withInput();
            }

            $employee = Employee::with('user')
                ->where('employee_number', $employeeNumber)
                ->first();
        }

        /* $submittedEmail = strtolower(trim($request->email)); */
        /* $registeredEmails = $employee */
        /*     ? array_filter([ */
        /*         strtolower(trim((string) $employee->personal_email)), */
        /*         strtolower(trim((string) optional($employee->user)->email)), */
        /*     ]) */
        /*     : []; */

        /* $hasMatchingEmail = $employee */
        /*     && in_array($submittedEmail, $registeredEmails, true); */
        /**/
        /* $hasSeparationLetter = $employee */
        /*     && EmployeeDocument::where('employee_id', $employee->id) */
        /*         ->where('document_type', '17') */
        /*         ->exists(); */

        /* if (!$hasMatchingEmail || !$hasSeparationLetter) { */
        /*     Alert::error( */
        /*         'Unable to submit. You are not eligible to submit this form. Please contact HR' */
        /*     )->persistent('Dismiss'); */
        /**/
        /*     return redirect()->back()->withInput(); */
        /* } */

        $data = $this->prepareFormData($request);
        if ($data === null) {
            return redirect()->back()->withInput();
        }

        $data['employee_number'] = $employeeNumber !== '' ? $employeeNumber : null;
        $data['employee_id'] = $employee ? $employee->id : null;
        $data['user_id'] = $employee ? optional($employee->user)->id : null;
        $data['submission_source'] = 'public';
        $data['submitted_at'] = now();

        $exitInterview = DB::transaction(function () use ($data, $references) {
            $data['reference_no'] = $references->next();
            return ExitInterview::create($data);
        });
        $this->completeMatchingInvitations($exitInterview);

        // confirmation
        try {
            Mail::to($exitInterview->email)->send(
                new ExitInterviewConfirmationMail($exitInterview)
            );
        } catch (\Throwable $exception) {
            \Log::warning(
                'Exit interview confirmation email failed for '
                .$exitInterview->reference_no
                .': '
                .$exception->getMessage()
            );
        }

        $hrHeadEmails = Employee::with('user')
            ->where('position', 'HR Head')
            ->where('status', 'Active')
            ->get()
            ->pluck('user.email')
            ->filter(function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })
            ->unique()
            ->values()
            ->all();

        if (!empty($hrHeadEmails)) {
            /* try { */
            /*     Mail::to($hrHeadEmails)->send( */
            /*         new ExitInterviewSubmittedMail( */
            /*             $exitInterview, */
            /*             $employee, */
            /*             url('exit-interviews/'.$exitInterview->id) */
            /*         ) */
            /*     ); */
            /* } catch (\Throwable $exception) { */
            /*     \Log::warning('Exit interview submitted but HR Head email notification failed: ' . $exception->getMessage()); */
            /* } */
        } else {
            \Log::warning('Exit interview submitted but no active HR Head work email was found.');
        }

        Alert::success('Exit interview submitted. Thank you!')->persistent('Dismiss');
        return redirect()->route('exit-interview.public.create');
    }

    public function store(
        Request $request,
        ExitInterviewReferenceService $references
    ) {
        $employee = auth()->user()->employee;

        $hasSeparationLetter = EmployeeDocument::where('employee_id', $employee->id)
            ->where('document_type', '17')
            ->whereNull('retracted_at')
            ->exists();

        if (!$hasSeparationLetter) {
            Alert::error('Access denied')->persistent('Dismiss');
            return redirect()->route('home');
        }

        $existingInterview = ExitInterview::where(
            'employee_number',
            $employee->employee_number
        )->first();

        if ($existingInterview) {
            Alert::warning('You have already submitted an exit interview')->persistent('Dismiss');
            return redirect('exit-interviews/'.$existingInterview->id);
        }

        $request->validate([
            'first_name'                  => 'required|string|max:100',
            'last_name'                   => 'required|string|max:100',
            'email'                       => 'required|email|max:255',
            'job_title'                   => 'required|string|max:150',
            'department'                  => 'required|string|max:150',
            'direct_supervisor_name'      => 'required|string|max:255',
            'work_location'               => 'required|string|max:150',
            'hire_date'                   => 'required|date|before_or_equal:today',
            'separation_date' => [
                'required',
                'date',
                'after_or_equal:hire_date',
            ],
            'is_separation_voluntary'     => 'required|boolean',
            'separation_type'             => [
                'required_if:is_separation_voluntary,0',
                'nullable',
                'string',
                'in:Retrenchment,Redundancy,End of Probationary Period due to Performance Gap,Termination due to Just Cause',
            ],
            'separation_comment'          => 'required_if:is_separation_voluntary,0|nullable|string|max:1000',
            'is_leaving_for_new_job'      => 'required_if:is_separation_voluntary,1|nullable|boolean',
            'reason_of_leave'             => 'required_if:is_separation_voluntary,1|nullable|array|min:1',
            'reason_of_leave.*'           => 'string|max:255',
            'reason_of_leave_other'       => 'nullable|string|max:255',
            'career_type_importance'      => 'required|array|min:1',
            'career_type_importance_other' => 'nullable|string|max:255',
            'new_job_offer_text'          => 'required_if:is_leaving_for_new_job,1|nullable|string|max:255',
            'rating_job_responsibilities' => 'required|integer|min:1|max:5',
            'rating_personal_goals'       => 'required|integer|min:1|max:5',
            'rating_work_environment'     => 'required|integer|min:1|max:5',
            'rating_immediate_superior'   => 'required|integer|min:1|max:5',
            'rating_salary'               => 'required|integer|min:1|max:5',
            'rating_benefits'             => 'required|integer|min:1|max:5',
            'most_liked_text'             => 'required|string',
            'least_liked_text'            => 'required|string',
            'understands_company_goals'   => 'required|boolean',
            'company_lives_core_values'   => 'required|boolean',
            'goals_explanation'       => 'required_if:understands_company_goals,0|nullable|string|max:255',
            'core_values_explanation' => 'required_if:company_lives_core_values,0|nullable|string|max:255',
            'frequently_performance_feedback' => 'required|string',
            'suggestion_company'          => 'nullable|string',
            'suggestion_department'       => 'nullable|string',
            'suggestion_other_employees'  => 'nullable|string',
            'other_feedback_text'         => 'nullable|string',
        ]);

        $data = $this->prepareFormData($request);
        if ($data === null) {
            return redirect()->back()->withInput();
        }
        $data['employee_number'] = auth()->user()->employee->employee_number;
        $data['employee_id'] = auth()->user()->employee->id;
        $data['user_id'] = auth()->id();
        $data['submission_source'] = 'internal';
        $data['submitted_at'] = now();

        $exitInterview = DB::transaction(function () use ($data, $references) {
            $data['reference_no'] = $references->next();
            return ExitInterview::create($data);
        });
        $this->completeMatchingInvitations($exitInterview);

        // confirmation
        try {
            Mail::to($exitInterview->email)->send(
                new ExitInterviewConfirmationMail($exitInterview)
            );
        } catch (\Throwable $exception) {
            \Log::warning(
                'Exit interview confirmation email failed for '
                .$exitInterview->reference_no
                .': '
                .$exception->getMessage()
            );
        }

        $hrHeadEmails = Employee::with('user')
            ->where('position', 'HR Head')
            ->where('status', 'Active')
            ->get()
            ->pluck('user.email')
            ->filter(function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })
            ->unique()
            ->values()
            ->all();

        if (!empty($hrHeadEmails)) {
            /* try { */
            /*     Mail::to($hrHeadEmails)->send( */
            /*         new ExitInterviewSubmittedMail( */
            /*             $exitInterview, */
            /*             $employee, */
            /*             url('exit-interviews/'.$exitInterview->id) */
            /*         ) */
            /*     ); */
            /* } catch (\Throwable $exception) { */
            /*     \Log::warning('Exit interview submitted but HR Head email notification failed: ' . $exception->getMessage()); */
            /* } */
        } else {
            \Log::warning('Exit interview submitted but no active HR Head work email was found.');
        }

        Alert::success('Answers submitted. Thank you!')->persistent('Dismiss');
        return redirect('exit-interviews/'.$exitInterview->id);
    }

    public function show($id) {
        $viewer = auth()->user()->employee;

        $interview = ExitInterview::findOrFail($id);

        $isAuthorized = [
            'President',
            'Human Resource Assistant',
            'Recruitment Supervisor', 
            'HR Head',
            'Organizational Development - HR Assistant',
            'Audit Head / PQD Head',
        ];

        $bdd = [
            'VP - Marketing and Branding',
            'VP - Business Development'
        ];

        $isOwner = $viewer && $viewer->id === $interview->employee_id;

        $hasFullAccess = $viewer
            && $viewer->status === 'Active'
            && in_array($viewer->position, $isAuthorized, true);

        $isBDD = $viewer
            && $viewer->status === 'Active'
            && in_array($viewer->position, $bdd, true);

        $isTDS = $interview->employee && $interview->employee->position === 'Trade Development Specialist';

        $canView = $isOwner || $hasFullAccess || ($isBDD && $isTDS);

        if (!$canView) {
            abort(403);
        }

        $header = 'exit_interviews';
        $exit_interview = $interview;
        return view('forms.exit_interviews.show', compact('header', 'exit_interview'));
    }


    private function completeMatchingInvitations(ExitInterview $exitInterview) {
        $employeeNumber = strtoupper(trim((string) $exitInterview->employee_number));
        $submittedEmail = strtolower(trim((string) $exitInterview->email));
        $firstName = strtolower(trim((string) $exitInterview->first_name));
        $lastName = strtolower(trim((string) $exitInterview->last_name));

        $pendingInvitations = function () {
            return ExitInterviewInvitation::whereNotNull('sent_at')
                ->whereNull('completed_at')
                ->whereNull('delivery_error');
        };

        $completedCount = 0;

        if ($employeeNumber !== '') {
            $completedCount = $pendingInvitations()
                ->whereRaw('UPPER(TRIM(employee_number)) = ?', [$employeeNumber])
                ->update([
                    'completed_at' => now(),
                    'exit_interview_id' => $exitInterview->id,
                ]);
        }

        if ($completedCount === 0 && $submittedEmail !== '') {
            $query = $pendingInvitations()
                ->whereRaw('LOWER(TRIM(recipient_email)) = ?', [$submittedEmail]);

            if ($firstName !== '') {
                $query->whereRaw('LOWER(TRIM(first_name)) = ?', [$firstName]);
            }

            if ($lastName !== '') {
                $query->whereRaw('LOWER(TRIM(last_name)) = ?', [$lastName]);
            }

            $query->update([
                'completed_at' => now(),
                'exit_interview_id' => $exitInterview->id,
            ]);
        }
    }

    /* joins checkbox selections for storage */
    private function prepareFormData(Request $request) {
        $data = $request->except([
            'reason_of_leave',
            'career_type_importance',
            'career_type_importance_other',
        ]);

        // Older authenticated forms do not yet send this field, so they retain
        // their existing voluntary-separation behavior.
        $isVoluntary = !$request->has('is_separation_voluntary')
            || (string) $request->input('is_separation_voluntary') === '1';

        $data['is_separation_voluntary'] = $isVoluntary;

        if ($isVoluntary) {
            $data['separation_type'] = null;
            $data['separation_comment'] = null;

            $reasons = $request->input('reason_of_leave', []);
            $other = trim((string) $request->input('reason_of_leave_other', ''));

            if (in_array('Others', $reasons) && $other === '') {
                Alert::error('Please specify your reason under "Others".')->persistent('Dismiss');
                return null;
            }

            if (in_array('Others', $reasons)) {
                $reasons[array_search('Others', $reasons)] = $other;
            }

            $data['reason_of_leave'] = implode(', ', $reasons);
            $data['reason_of_leave_other'] = $other !== '' ? $other : null;
        } else {
            $data['is_leaving_for_new_job'] = null;
            $data['new_job_offer_text'] = null;
            $data['reason_of_leave'] = null;
            $data['reason_of_leave_other'] = null;
            $data['separation_comment'] = trim((string) $request->input('separation_comment'));
        }

        $careers = $request->input('career_type_importance', []);
        $careerOther = trim((string) $request->input('career_type_importance_other', ''));

        if (in_array('Others', $careers) && $careerOther === '') {
            Alert::error('Please specify your other career opportunity under "Others".')->persistent('Dismiss');
            return null;
        }

        if (in_array('Others', $careers)) {
            $careers[array_search('Others', $careers)] = $careerOther;
        }

        $data['career_type_importance'] = implode(', ', $careers);

        return $data;
    }

}
