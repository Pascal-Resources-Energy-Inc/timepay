<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeeCoe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\ApproverSetting;

class EmployeeCoeController extends Controller
{
    public function coe(Request $request)
    {
        $today = date('Y-m-d');
        $from = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $to = $request->to ?? date('Y-m-d');
        $status = $request->status ?? '';

        $get_approvers = new EmployeeApproverController;

        $coes = EmployeeCoe::with('user')
            ->where('user_id', auth()->user()->id)
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->whereDate('applied_date', '>=', $from)
            ->whereDate('created_at', '<=', $to)
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

    public function new(Request $request)
    {
        $request->validate([
            'reason_for_request' => 'required|string|in:Plain,With Salary',
            'employment_status' => 'required|string|in:Active,Separated',
            'designation' => 'required|string|max:255',
            'purpose' => 'required|string|max:1000',
            'receive_method' => 'required|string|in:Email,Viber',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $employee = $user->employee;

        $coe = new EmployeeCoe;
        $coe->user_id = $user->id;
        $coe->schedule_id = $employee->schedule_id ?? null;
        $coe->reason_for_request = $request->reason_for_request;
        $coe->employment_status = $request->employment_status;
        $coe->hiring_date = $employee->original_date_hired;
        $coe->designation = $request->designation;
        $coe->name = $employee->first_name . ' ' . $employee->last_name;
        $coe->email = $employee->personal_email;
        $coe->purpose = $request->purpose;
        $coe->receive_method = $request->receive_method;
        $coe->additional_notes = $request->additional_notes;
        $coe->applied_date = now();
        $coe->status = 'Pending';
        $coe->created_by = $user->id;
        
        try {
            $coe->save();
            Alert::success('COE request submitted successfully')->persistent('Dismiss');
        } catch (\Exception $e) {
            Alert::error('Failed to submit COE request: ' . $e->getMessage())->persistent('Dismiss');
        }
        
        return back();
    }
    
    public function edit_coe(Request $request, $id)
    {
        $request->validate([
            'reason_for_request' => 'required|string|in:Plain,With Salary',
            'employment_status' => 'required|string|in:Active,Separated',
            'designation' => 'required|string|max:255',
            'purpose' => 'required|string|max:1000',
            'receive_method' => 'required|string|in:Email,Viber',
            'additional_notes' => 'nullable|string|max:1000',
        ]);


        $coe = EmployeeCoe::findOrFail($id);
        if ($coe->user_id !== Auth::id()) {
            Alert::error('Unauthorized access')->persistent('Dismiss');
            return back();
        }

        if ($coe->status === 'Approved') {
            Alert::warning('Cannot edit approved COE request')->persistent('Dismiss');
            return back();
        }

        $coe->reason_for_request = $request->reason_for_request;
        $coe->employment_status = $request->employment_status;
        $coe->designation = $request->designation;
        $coe->purpose = $request->purpose;
        $coe->receive_method = $request->receive_method;
        $coe->additional_notes = $request->additional_notes;
        $coe->updated_at = now();
        
        $coe->save();

        Alert::success('COE request updated successfully')->persistent('Dismiss');
        return back();
    }

     public function disable_coe($id)
    {
        EmployeeCoe::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('COE has been cancelled.')->persistent('Dismiss');
        return back();
    }


}
