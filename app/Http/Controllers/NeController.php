<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\EmployeeApproverController;
use App\NumberEnrollment;
use App\EmployeeNe;
use App\Employee;
use App\Company;
use Illuminate\Http\Request;

use App\Exports\EmployeeLeaveExport;
use Excel;

class NeController extends Controller
{
   public function NeDetails()
   {
       $ne_report = EmployeeNe::get();
       return view(
           'reports.ne_report',
           array(
               'header' => 'reports',
               'ne_report' => $ne_report,
           )
       );
   }

   public function ne_report(Request $request)
   {   
       $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
       $companies = Company::whereHas('employee_has_company')
                               ->whereIn('id',$allowed_companies)
                               ->get();
       
       $company = isset($request->company) ? $request->company : [];
       $employee = isset($request->employee) ? $request->employee : [];
       $from = isset($request->from) ? $request->from : "";
       $to =  isset($request->to) ? $request->to : "";
       $status =  isset($request->status) ? $request->status : "";
       $employee_nes = [];
       
       $employees = Employee::with('user_info')
                           ->where('status','Active')
                           ->whereHas('company', function($q) use($allowed_companies) {
                               $q->whereIn('id', $allowed_companies);
                           })
                           ->get();
       
       if(isset($request->from) && isset($request->to)){
           $query = EmployeeNe::with('user','employee.company')
               ->whereDate('applied_date','>=',$from)
               ->whereDate('applied_date','<=',$to);

           $query->whereHas('employee', function($q) use($allowed_companies) {
               $q->whereIn('company_id', $allowed_companies);
           });
           
           if(!empty($employee) && is_array($employee)) {
               $user_ids = Employee::whereIn('id', $employee)->pluck('user_id')->toArray();
               $query->whereIn('user_id', $user_ids);
           }

           if($status && $status != "ALL") {
               $query->where('status', $status);
           }
           
           $filtered_records = $query->orderBy('user_id')
               ->orderBy('applied_date', 'desc')
               ->get();

           $employee_nes = $filtered_records->groupBy('user_id')->map(function($records) {
               $latest = $records->first();

               $previous_record = EmployeeNe::where('user_id', $latest->user_id)
                   ->where('applied_date', '<', $latest->applied_date)
                   ->whereHas('employee', function($q) use($latest){
                       $q->where('company_id', $latest->employee->company_id);
                   })
                   ->orderBy('applied_date', 'desc')
                   ->first();
               
               $latest->old_phonenumber = $previous_record ? $previous_record->cellphone_number : null;
               
               return $latest;
           })->values();
       }
       
       return view('reports.ne_report', array(
           'header' => 'reports',
           'company'=>$company,
           'employee'=>$employee,
           'from'=>$from,
           'to'=>$to,
           'status'=>$status,
           'employee_nes' => $employee_nes,
           'companies' => $companies,
           'employees' => $employees
       ));
   }

    
    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $status =  isset($request->status) ? $request->status : "";
        $company_detail = Company::where('id',$company)->first();
        
        return Excel::download(new EmployeeNeExport($company,$from,$to,$status), 'Number Enrollment ' . $company_detail->company_code . ' ' . $from . ' to ' . $to . '.xlsx');
    }
}