<?php

namespace App\Http\Controllers;

use App\Employee;
use App\EmployeeDocument;
use App\Mail\ExitInterviewInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeDocumentController extends Controller {
    public function index() {

        $documentTypes = documentTypes();
        $employeeSubmitted = collect($documentTypes)->keys();

        // $employeeSubmitted = EmployeeDocument::whereIn('document_type', $employeeSubmitted)->get();
        // dd($employeeSubmitted);

        $employee = Employee::with([
            'employeeDocuments' => function($query)use($employeeSubmitted) {
                $query->whereIn('document_type', $employeeSubmitted);
            }
        ])
            ->where('status', 'Active')
        // ->where('id', 117)
            ->paginate(10);

        return view('hr-portal.employee-document',
            array(
                'header' => 'hrPortal',
                'employee' => $employee
            )
        );
    }

    public function retract($id) {
        abort_unless(
            checkUserPrivilege('employees_edit', auth()->user()->id) === 'yes',
            403
        );

        $employeeDocument = EmployeeDocument::where('id', $id)
            ->where('document_type', '17')
            ->firstOrFail();

        if ($employeeDocument->retracted_at) {
            Alert::info('This document has already been retracted.')
                ->persistent('Dismiss');

            return back();
        }

        $employeeDocument->retracted_at = now();
        $employeeDocument->retracted_by = auth()->id();
        $employeeDocument->save();

        Alert::success('Separation Letter has been retracted.')->persistent('Dismiss');

        return back();
    }


    public function upload(Request $request) {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048'
        ]);

        $employeeDocument = EmployeeDocument::where('employee_id', $request->employee_id)
            ->where('document_type', $request->document_type)
            ->first();

        if (!empty($employeeDocument)) {
            $employeeDocument->employee_id = $request->employee_id;
            $employeeDocument->document_type = $request->document_type;

            if ($request->hasFile('file')) {
                $files = $request->file('file');
                $fileName = time().'-'.$files->getClientOriginalName();
                $files->move(public_path().'/employee_documents/', $fileName);
                $employeeDocument->file_path = '/employee_documents/'.$fileName;
                $employeeDocument->file_name = $fileName;
            }

            if ((string) $employeeDocument->document_type === '17') {
                $employeeDocument->retracted_at = null;
                $employeeDocument->retracted_by = null;
            }

            $employeeDocument->save();
        }
        else {
            $files = $request->file('file');
            $fileName = time().'-'.$files->getClientOriginalName();
            $files->move(public_path().'/employee_documents/', $fileName);

            $employeeDocument = new EmployeeDocument;
            $employeeDocument->employee_id = $request->employee_id;
            $employeeDocument->document_type = $request->document_type;
            $employeeDocument->file_path = '/employee_documents/'.$fileName;
            $employeeDocument->file_name = $fileName;
            $employeeDocument->save();
        }

        if ((string) $employeeDocument->document_type === '17') {
            $employee = Employee::with('user')->find($employeeDocument->employee_id);

            if (!$employee) {
                Alert::warning('The Separation Letter was uploaded, but the employee was not found.')->persistent('Dismiss');
                return back();
            }

            $recipientEmails = array_values(array_unique(array_filter([
                trim((string) $employee->personal_email),
                trim((string) optional($employee->user)->email),
            ])));

            if (empty($recipientEmails)) {
                Alert::warning('The Separation Letter was uploaded, but the employee does not have an email address.')->persistent('Dismiss');
                return back();
            }

            try {
                $hrHeadEmail = Employee::with('user')
                    ->where('position', 'HR Head')
                    ->where('status', 'Active')
                    ->whereHas('user', function ($query) {
                        $query->whereNotNull('email');
                    })
                    ->firstOrFail()
                    ->user
                    ->email;

                Mail::to($recipientEmails)->send(
                    new ExitInterviewInvitationMail(
                        $employee,
                        url('/external/exit-interview'),
                        null,
                        $hrHeadEmail
                    )
                );
            } catch (\Throwable $exception) {
                Alert::warning('The Separation Letter was uploaded, but the email was not sent: ' .$exception->getMessage())->persistent('Dismiss');
                return back();
            }

            Alert::success('Attachment uploaded and exit interview has been sent to email.')->persistent('Dismiss');
            return back();
        }

        Alert::success('Successfully Upload.')->persistent('Dismiss');
        return back();
    }
}
