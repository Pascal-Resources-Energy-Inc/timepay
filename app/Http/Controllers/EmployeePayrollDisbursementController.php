<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeePd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


class EmployeePayrollDisbursementController extends Controller
{
    public function pd(Request $request)
        {
            $today = date('Y-m-d');
            $from = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
            $to = $request->to ?? date('Y-m-d');
            $status = $request->status ?? '';

            $get_approvers = new EmployeeApproverController;

            $pds = EmployeePd::with('user')
                ->where('user_id', auth()->user()->id)
                ->whereDate('applied_date', '>=', $from)
                ->whereDate('applied_date', '<=', $to)
                ->when($status, function($query, $status) {
                    return $query->where('status', $status);
                })
                ->orderBy('created_at', 'DESC')
                ->get();
                
            $pds_all = EmployeePd::with('user')
                ->where('user_id', auth()->user()->id)
                ->get();

            $all_approvers = $get_approvers->get_approvers(auth()->user()->id);

            $pd_approvers = \App\ApproverSetting::with('user.employee')
                ->where('type_of_form', 'pd')
                ->where('status', 'Active')
                ->get();

            $getApproverForEmployee = function($employee) use ($pd_approvers) {
                $employee_company = $employee->company_code ?? $employee->company_id ?? null;
                
                if ($employee_company) {
                    foreach($pd_approvers as $approver) {
                        $approver_company = $approver->user->employee->company_code ?? $approver->user->employee->company_id ?? null;
                        if ($approver_company == $employee_company) {
                            return $approver;
                        }
                    }
                }
                
                return $pd_approvers->first();
            };

            return view('forms.payrolldisbursement.payrolldisbursement', [
                'header' => 'forms',
                'all_approvers' => $all_approvers,
                'pds' => $pds,
                'pds_all' => $pds_all,
                'from' => $from,
                'to' => $to,
                'status' => $status,
                'pd_approvers' => $pd_approvers,
                'getApproverForEmployee' => $getApproverForEmployee,
            ]);
        }
    
    public function new(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cell_phone_number' => 'required|string|size:11|regex:/^[0-9]+$/',
            'employee_email' => 'required|email|max:255',
            'reason_for_request' => 'required|string|in:Captured Card,Defective Card,Lost Card,Transition BDO,New Employee',
            'Other' => 'nullable|string|max:255',
            'comment' => 'required|string|max:1000',
            'disbursement_account' => 'required|string|in:BDO Payroll,BDO Personal,Metrobank Paycard,Metrobank Personal,Gcash Personal,Gcash Other',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ]);

        // Create new PayrollDisbursement record
        $payrollDisbursement = new EmployeePd;
        $payrollDisbursement->user_id = Auth::id();
        
        // Get employee schedule if needed
        $emp = Employee::where('user_id', auth()->user()->id)->first();
        if ($emp) {
            $payrollDisbursement->schedule_id = $emp->schedule_id;
        }

        // Employee Personal Information
        $payrollDisbursement->employee_number = $request->employee_number;
        $payrollDisbursement->designation = $request->designation;
        $payrollDisbursement->name = $request->first_name . ' ' . $request->last_name;
        $payrollDisbursement->cell_phone_number = $request->cell_phone_number;
        $payrollDisbursement->employee_email = $request->employee_email;

        // Disbursement Account Details
        $payrollDisbursement->reason_for_request = $request->reason_for_request;
        $payrollDisbursement->other_reason = $request->Other;
        $payrollDisbursement->comment = $request->comment;
        $payrollDisbursement->disbursement_account = $request->disbursement_account;
        $payrollDisbursement->account_name = $request->account_name;
        $payrollDisbursement->account_number = $request->account_number;

        // System fields
        $payrollDisbursement->applied_date = now();
        $payrollDisbursement->status = 'Pending';
        $payrollDisbursement->created_by = Auth::id();
        
        $payrollDisbursement->save();

        Alert::success('Payroll Disbursement request submitted successfully')->persistent('Dismiss');
        return back();
    }

    public function edit_pd(Request $request, $id)
    {
        $request->validate([
            'employee_number' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cell_phone_number' => 'required|string|size:11|regex:/^[0-9]+$/',
            'employee_email' => 'required|email|max:255',
            'reason_for_request' => 'required|string|in:Captured Card,Defective Card,Lost Card,Transition BDO,New Employee',
            'Other' => 'nullable|string|max:255',
            'comment' => 'required|string|max:1000',
            'disbursement_account' => 'required|string|in:BDO Payroll,BDO Personal,Metrobank Paycard,Metrobank Personal,Gcash Personal,Gcash Other',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ]);


        $payrollDisbursement = EmployeePd::findOrFail($id);
        if ($payrollDisbursement->user_id !== Auth::id()) {
            Alert::error('Unauthorized access')->persistent('Dismiss');
            return back();
        }

        if ($payrollDisbursement->status === 'Approved') {
            Alert::warning('Cannot edit approved payroll disbursement request')->persistent('Dismiss');
            return back();
        }

        $payrollDisbursement->employee_number = $request->employee_number;
        $payrollDisbursement->designation = $request->designation;
        $payrollDisbursement->name = $request->first_name . ' ' . $request->last_name;
        $payrollDisbursement->cell_phone_number = $request->cell_phone_number;
        $payrollDisbursement->employee_email = $request->employee_email;
        $payrollDisbursement->reason_for_request = $request->reason_for_request;
        $payrollDisbursement->other_reason = $request->Other;
        $payrollDisbursement->comment = $request->comment;
        $payrollDisbursement->disbursement_account = $request->disbursement_account;
        $payrollDisbursement->account_name = $request->account_name;
        $payrollDisbursement->account_number = $request->account_number;
        $payrollDisbursement->updated_at = now();
        
        $payrollDisbursement->save();

        Alert::success('Payroll Disbursement request updated successfully')->persistent('Dismiss');
        return back();
    }
    
    public function disable_pd($id)
    {
        EmployeePd::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('Payroll Disbursement has been cancelled.')->persistent('Dismiss');
        return back();
    }

    
}
