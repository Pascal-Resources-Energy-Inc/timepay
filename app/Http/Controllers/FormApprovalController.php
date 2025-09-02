<?php

namespace App\Http\Controllers;
use Carbon\Carbon; 
use App\Employee;
use App\EmployeeLeave;
use App\PayInstruction;
use App\EmployeePd;
use App\EmployeeWfh;
use App\EmployeeOvertime;
use App\EmployeeTo;
use App\EmployeeCoe;
use App\EmployeeNe;
use App\EmployeeDtr;
use App\ApprovalByAmount;
use App\AttendanceLog;
use App\Attendance;
use App\EmployeeApprover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdStatusNotification;

class FormApprovalController extends Controller
{

    public function form_leave_approval (Request $request)
    { 

        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $filter_request_to_cancel = '';
        if(isset($request->request_to_cancel)){
            $filter_status = 'Approved';
            $filter_request_to_cancel = isset($request->request_to_cancel) ? $request->request_to_cancel : '';
        }
        
        $approver_id = auth()->user()->id;
        $leaves = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->when($filter_status, function($q) use($filter_status){
                                    $q->where('status',$filter_status);
                                })
                                ->when($filter_request_to_cancel, function($q) use($filter_request_to_cancel){
                                    $q->where('request_to_cancel',$filter_request_to_cancel);
                                })
                                // ->whereDate('created_at','>=',$from_date)
                                // ->whereDate('created_at','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();
        
        $user_ids = EmployeeApprover::select('user_id')->where('approver_id',$approver_id)->pluck('user_id')->toArray();

        $for_approval = EmployeeLeave::whereIn('user_id',$user_ids)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->where('status','Pending')
                                ->count();
        $approved = EmployeeLeave::whereIn('user_id',$user_ids)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->where('status','Approved')
                                ->count();
        $declined = EmployeeLeave::whereIn('user_id',$user_ids)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->where('status','Declined')
                                ->count();
        $request_to_cancel = EmployeeLeave::whereIn('user_id',$user_ids)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->where('request_to_cancel','1')
                                ->count();
        
        session(['pending_leave_count'=>$for_approval + $request_to_cancel]);

        return view('for-approval.leave-approval',
        array(
            'header' => 'for-approval',
            'leaves' => $leaves,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'request_to_cancel' => $request_to_cancel,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
        ));

    }

    public function approveLeave(Request $request, $id){

        $employee_leave  = EmployeeLeave::where('id', $id)
                                            ->first();

        if($employee_leave){
            $level = '';
            if($employee_leave->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_leave->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver)
                {
                    if($employee_approver->as_final == 'on'){
                        EmployeeLeave::Where('id', $id)->update([
                            'approved_date' => date('Y-m-d'),
                            'status' => 'Approved',
                            'approval_remarks' => $request->approval_remarks,
                            'level' => 1,
                            'approved_by' => auth()->user()->id
                        ]);
                    }else{
                        EmployeeLeave::Where('id', $id)->update([
                            'level' => 1,
                            'approved_by' => auth()->user()->id
                        ]);
                    }
                }
                else
                {
                    EmployeeLeave::Where('id', $id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                        'approved_by' => auth()->user()->id
                    ]);
                }
               
                
            }
            else if($employee_leave->level == 1){
                EmployeeLeave::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                    'approved_by' => auth()->user()->id
                ]);
            }

            Alert::success('Leave has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineLeave(Request $request, $id){
        EmployeeLeave::Where('id', $id)->update([
                        'status' => 'Declined',
                        'approval_remarks' => $request->approval_remarks,
                        'approved_by' => auth()->user()->id
                    ]);
        Alert::success('Leave has been declined.')->persistent('Dismiss');
        return back();
    }

    public function approveLeaveAll(Request $request){
        
        $ids = json_decode($request->ids,true);

        $count = 0;
        if(count($ids) > 0){
            
            foreach($ids as $id){
                $employee_dtr = EmployeeLeave::where('id', $id)->first();
                if($employee_dtr){
                    $level = '';
                    $employee_approver = EmployeeApprover::where('user_id', $employee_dtr->user_id)->where('approver_id', auth()->user()->id)->first();
                    if($employee_dtr->level == 0){
                        if($employee_approver->as_final == 'on'){
                            EmployeeLeave::Where('id', $id)->update([
                                'approved_date' => date('Y-m-d'),
                                'status' => 'Approved',
                                'approval_remarks' => 'Approved',
                                'level' => 1,
                                'approved_by' => auth()->user()->id
                            ]);
                            $count++;
                        }else{
                            EmployeeLeave::Where('id', $id)->update([
                                'approval_remarks' => 'Approved',
                                'level' => 1,
                                'approved_by' => auth()->user()->id
                            ]);
                            $count++;
                        }
                    }
                    else if($employee_dtr->level == 1){
                        if($employee_approver->as_final == 'on'){
                            EmployeeLeave::Where('id', $id)->update([
                                'approved_date' => date('Y-m-d'),
                                'status' => 'Approved',
                                'approval_remarks' => 'Approved',
                                'level' => 2,
                                'approved_by' => auth()->user()->id
                            ]);
                            $count++;
                        }
                    }
                }
            }

            return $count;

        }else{
            return 'error';
        }
    }

    public function disapproveLeaveAll(Request $request){
        
        $ids = json_decode($request->ids,true);

        $count = 0;
        if(count($ids) > 0){
            
            foreach($ids as $id){
                EmployeeLeave::Where('id', $id)->update([
                    'status' => 'Declined',
                    'approval_remarks' => 'Declined',
                    'approved_by' => auth()->user()->id
                ]);

                $count++;
            }

            return $count;

        }else{
            return 'error';
        }
    }

    public function form_overtime_approval(Request $request)
    { 
        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $overtimes = EmployeeOvertime::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();

        $user_ids = EmployeeApprover::select('user_id')->where('approver_id',$approver_id)->pluck('user_id')->toArray();

        $for_approval = EmployeeOvertime::whereIn('user_id',$user_ids)
                                ->where('status','Pending')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();
                                
        $approved = EmployeeOvertime::whereIn('user_id',$user_ids)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->where('status','Approved')
                                ->count();

        $declined = EmployeeOvertime::whereIn('user_id',$user_ids)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->where('status','Declined')
                                ->count();
        
        session(['pending_overtime_count'=>$for_approval]);

        return view('for-approval.overtime-approval',
        array(
            'header' => 'for-approval',
            'overtimes' => $overtimes,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
        ));

    }

    public function approveOvertime(Request $request, EmployeeOvertime $employee_overtime){

    
        if($employee_overtime){
            $level = '';
            if($employee_overtime->level == 0){

                $employee_approver = EmployeeApprover::where('user_id', $employee_overtime->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver == null)
                {
                    $ot_approved_hrs = $request->ot_approved_hrs;
                    EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                        'break_hrs' => $request->break_hrs,
                        'ot_approved_hrs' => $ot_approved_hrs,
                        'approved_by' => auth()->user()->id
                    ]);
                }
                else
                {
                    if($employee_approver->as_final == 'on'){
                        $ot_approved_hrs = $request->ot_approved_hrs;
                        EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                            'approved_date' => date('Y-m-d'),
                            'status' => 'Approved',
                            'approval_remarks' => $request->approval_remarks,
                            'level' => 1,
                            'break_hrs' => $request->break_hrs,
                            'ot_approved_hrs' => $ot_approved_hrs,
                            'approved_by' => auth()->user()->id
                        ]);
                    }else{
                        EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                            'approval_remarks' => $request->approval_remarks,
                            'level' => 1,
                            'break_hrs' => $request->break_hrs,
                            'ot_approved_hrs' => $request->ot_approved_hrs,
                            'approved_by' => auth()->user()->id
                        ]);
                    }
                }
               
            }
            else if($employee_overtime->level == 1){
                $ot_approved_hrs = $request->ot_approved_hrs;
                EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                    'break_hrs' => $request->break_hrs,
                    'ot_approved_hrs' => $ot_approved_hrs,
                    'approved_by' => auth()->user()->id
                ]);
            }
            Alert::success('Overtime has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function timekeeperApproveOvertime(Request $request, EmployeeOvertime $employee_overtime){

    
        if($employee_overtime){
            
                $ot_approved_hrs = $request->ot_approved_hrs;
                EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                    'approval_remarks' => $request->approval_remarks,
                    'break_hrs' => $request->break_hrs,
                    'ot_approved_hrs' => $ot_approved_hrs,
                    'approved_by' => auth()->user()->id
                ]);
            Alert::success('Overtime has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineOvertime(Request $request,$id){
        EmployeeOvertime::Where('id', $id)->update([
                            'status' => 'Declined',
                            'approval_remarks' => $request->approval_remarks,
                            'approved_by' => auth()->user()->id
                        ]);
        Alert::success('Overtime has been declined.')->persistent('Dismiss');
        return back();
    }


    public function form_wfh_approval(Request $request)
    { 
        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $wfhs = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();
        
        $user_ids = EmployeeApprover::select('user_id')->where('approver_id',$approver_id)->pluck('user_id')->toArray();

        $for_approval = EmployeeWfh::whereIn('user_id',$user_ids)
                                ->where('status','Pending')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();
        $approved = EmployeeWfh::whereIn('user_id',$user_ids)
                                ->where('status','Approved')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();
        $declined = EmployeeWfh::whereIn('user_id',$user_ids)
                                ->where('status','Declined')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();
        
        session(['pending_wfh_count'=>$for_approval]);

        return view('for-approval.wfh-approval',
        array(
            'header' => 'for-approval',
            'wfhs' => $wfhs,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
        ));

    }

    public function approveWfh(Request $request,$id){

        $employee_wfh = EmployeeWfh::where('id', $id)
                                            ->first();

        if($employee_wfh){
            $level = '';
            if($employee_wfh->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_wfh->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver->as_final == 'on'){
                    EmployeeWfh::Where('id', $id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approve_percentage' => $request->approve_percentage,
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                    ]);
                }else{
                    EmployeeWfh::Where('id', $id)->update([
                        'level' => 1,
                        'approve_percentage' => $request->approve_percentage,
                        'approval_remarks' => $request->approval_remarks,
                    ]);
                }
            }
            else if($employee_wfh->level == 1){
                EmployeeWfh::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approve_percentage' => $request->approve_percentage,
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                ]);
            }
            Alert::success('Wfh has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineWfh(Request $request,$id){
        EmployeeWfh::Where('id', $id)->update([
                'status' => 'Declined',
                'approval_remarks' => $request->approval_remarks,
        ]);
        Alert::success('Wfh has been declined.')->persistent('Dismiss');
        return back();
    }

    public function approveWfhAll(Request $request){
        
        $ids = json_decode($request->ids,true);

        $count = 0;
        if(count($ids) > 0){
            
            foreach($ids as $id){
                $employee_dtr = EmployeeWfh::where('id', $id)->first();
                if($employee_dtr){
                    $level = '';
                    $employee_approver = EmployeeApprover::where('user_id', $employee_dtr->user_id)->where('approver_id', auth()->user()->id)->first();
                    if($employee_dtr->level == 0){
                        if($employee_approver->as_final == 'on'){
                            EmployeeWfh::Where('id', $id)->update([
                                'approved_date' => date('Y-m-d'),
                                'status' => 'Approved',
                                'approval_remarks' => 'Approved',
                                'level' => 1,
                            ]);
                            $count++;
                        }else{
                            EmployeeWfh::Where('id', $id)->update([
                                'approval_remarks' => 'Approved',
                                'level' => 1
                            ]);
                            $count++;
                        }
                    }
                    else if($employee_dtr->level == 1){
                        if($employee_approver->as_final == 'on'){
                            EmployeeWfh::Where('id', $id)->update([
                                'approved_date' => date('Y-m-d'),
                                'status' => 'Approved',
                                'approval_remarks' => 'Approved',
                                'level' => 2,
                            ]);
                            $count++;
                        }
                    }
                }
            }

            return $count;

        }else{
            return 'error';
        }
    }

    public function disapproveWfhAll(Request $request){
        
        $ids = json_decode($request->ids,true);

        $count = 0;
        if(count($ids) > 0){
            
            foreach($ids as $id){
                EmployeeWfh::Where('id', $id)->update([
                    'status' => 'Declined',
                    'approval_remarks' => 'Declined',
                ]);

                $count++;
            }

            return $count;

        }else{
            return 'error';
        }
    }

    public function form_to_approval(Request $request)
        {
            $today = date('Y-m-d');
            $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
            $to_date = $request->to ?? date('Y-m-d');
            $limit = $request->limit ?? 10;

            $filter_status = $request->status ?? 'Pending';
            $approver_id = auth()->user()->id;

            $tos = EmployeeTo::with(['approver.approver_info', 'user'])
                ->whereHas('approver', function ($q) use ($approver_id) {
                    $q->where('approver_id', $approver_id);
                })
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date);

            if ($filter_status !== 'All') {
                $tos->where('status', $filter_status);
            } else {
                $tos->where('status', '!=', 'Cancelled');
            }

            $tos = $tos->orderBy('created_at', 'DESC')->paginate($limit);

            $approvalThreshold = ApprovalByAmount::orderBy('higher_than', 'desc')->first();

            $tos->getCollection()->transform(function ($to) use ($approvalThreshold) {
                $totalAmount = $to->totalamount_total;
                $to->show_final_approver = false;
                $to->final_approver = null;

                $approvers = $to->approver ?? collect();
                $first = $approvers->first();
                $finalApprover = $approvers->filter(fn($a) => ($a->as_final ?? '') === 'on')->first();

                if ($approvalThreshold && $totalAmount > $approvalThreshold->higher_than) {
                    $to->approver = collect([$first, $finalApprover])->filter()->unique('id');

                    if ($finalApprover && $finalApprover->approver_info) {
                        $to->final_approver = $finalApprover->approver_info;
                        $to->show_final_approver = true;
                    }
                } else {
                    $to->approver = collect([$first])->filter()->unique('id');
                }

                return $to;
            });
            
            $user_ids = EmployeeApprover::select('user_id')
                ->where('approver_id', $approver_id)
                ->pluck('user_id')
                ->toArray();

            $for_approval = EmployeeTo::whereIn('user_id', $user_ids)
                ->where('status', 'Pending')
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->count();

            $approved = EmployeeTo::whereIn('user_id', $user_ids)
                ->where('status', 'Approved')
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->count();

            $declined = EmployeeTo::whereIn('user_id', $user_ids)
                ->where('status', 'Declined')
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->count();

            session(['pending_to_count' => $for_approval]);

            return view('for-approval.travelordermanager', [
                'header' => 'for-approval',
                'tos' => $tos,
                'for_approval' => $for_approval,
                'approved' => $approved,
                'declined' => $declined,
                'approver_id' => $approver_id,
                'from' => $from_date,
                'to' => $to_date,
                'status' => $filter_status,
                'limit' => $limit,
                'approvalThreshold' => $approvalThreshold
            ]);
        }


     public function approveTo(Request $request, $id)
        {
            $employee_to = EmployeeTo::find($id);

            if (!$employee_to) {
                Alert::error('TO not found.')->persistent('Dismiss');
                return back();
            }

            $current_user_id = auth()->user()->id;

            $approvalThreshold = ApprovalByAmount::orderBy('higher_than', 'desc')->first();
            $thresholdAmount = $approvalThreshold ? $approvalThreshold->higher_than : 5000;

            $approvers = EmployeeApprover::where('user_id', $employee_to->user_id)
                                ->orderBy('id')
                                ->get();

            $approver_ids = $approvers->pluck('approver_id')->toArray();
            $current_level = $employee_to->level;
            $current_approver_index = array_search($current_user_id, $approver_ids);

            if ($current_approver_index === false) {
                Alert::error('You are not in the approval flow.')->persistent('Dismiss');
                return back();
            }

            $amount_over_threshold = $employee_to->totalamount_total > $thresholdAmount;

            $final_approver_index = $approvers->search(function($a) {
                return ($a->as_final ?? '') === 'on';
            });

            if (!$amount_over_threshold) {
                if ($current_approver_index === 0 && $final_approver_index !== 0) {
                    $employee_to->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => $request->approval_remarks,
                        'level' => $current_level + 1,
                        'approved_by' => $current_user_id
                    ]);
                } else {
                    Alert::error('Only the first approver can approve TOs under the threshold.')->persistent('Dismiss');
                }
                return back();
            }

            if ($current_approver_index === 0 && $final_approver_index !== 0) {
                $employee_to->update([
                    'approved_date' => date('Y-m-d'),
                    'level' => $final_approver_index,
                    'approval_remarks' => $request->approval_remarks,
                    'approved_by' => $current_user_id,
                    'status' => 'Pending'
                ]);
            } elseif ($current_approver_index === $final_approver_index) {
                $employee_to->update([
                    'approved_head_division' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approval_remarks2' => $request->approval_remarks,
                    'level' => $current_level + 1,
                    'approved_by_head_division' => $current_user_id
                ]);
            } else {
                Alert::error('You are not allowed to approve this TO.')->persistent('Dismiss');
            }

            Alert::success('TO has been approved.')->persistent('Dismiss');
            return back();
        }

        public function approveToAll(Request $request)
            {
                $ids = json_decode($request->ids, true);
                $count = 0;
                $current_user_id = auth()->id();

                $approvalThreshold = ApprovalByAmount::orderBy('higher_than', 'desc')->first();
                $thresholdAmount = $approvalThreshold ? $approvalThreshold->higher_than : 5000;

                if (!is_array($ids) || empty($ids)) {
                    return 0;
                }

                foreach ($ids as $id) {
                    $employee_to = EmployeeTo::find($id);
                    if (!$employee_to) {
                        continue;
                    }

                    $approvers = EmployeeApprover::where('user_id', $employee_to->user_id)
                                    ->orderBy('id')
                                    ->get();

                    $approver_ids = $approvers->pluck('approver_id')->toArray();
                    $current_level = $employee_to->level;
                    $current_approver_index = array_search($current_user_id, $approver_ids);

                    if ($current_approver_index === false) {
                        continue;
                    }

                    $amount_over_threshold = $employee_to->totalamount_total > $thresholdAmount;

                    $final_approver_index = $approvers->search(function($a) {
                        return ($a->as_final ?? '') === 'on';
                    });

                    if (!$amount_over_threshold) {
                        if ($current_approver_index === 0 && $final_approver_index !== 0) {
                            $employee_to->update([
                                'approved_date' => date('Y-m-d'),
                                'status' => 'Approved',
                                'approval_remarks' => 'Approved',
                                'level' => $current_level + 1,
                                'approved_by' => $current_user_id
                            ]);
                            $count++;
                        }
                        continue;
                    }

                    if ($current_approver_index === 0 && $final_approver_index !== 0) {
                        $employee_to->update([
                            'approved_date' => date('Y-m-d'),
                            'level' => $final_approver_index,
                            'approval_remarks' => 'Approved',
                            'approved_by' => $current_user_id,
                            'status' => 'Pending'
                        ]);
                        $count++;
                    } elseif ($current_approver_index === $final_approver_index) {
                        $employee_to->update([
                            'approved_head_division' => date('Y-m-d'),
                            'status' => 'Approved',
                            'approval_remarks2' => 'Approved',
                            'level' => $current_level + 1,
                            'approved_by_head_division' => $current_user_id
                        ]);
                        $count++;
                    }
                }

                return $count;
            }

        public function declineTo(Request $request, $id)
            {
                $employee_to = EmployeeTo::findOrFail($id);
                $user_id = auth()->id();

                $final_approver_id = EmployeeApprover::where('user_id', $employee_to->user_id)
                    ->where('as_final', 'on')
                    ->value('approver_id');

                $remarksField = $user_id == $final_approver_id ? 'approval_remarks2' : 'approval_remarks';
                $approvedByField = $user_id == $final_approver_id ? 'approved_by_head_division' : 'approved_by';

                $employee_to->update([
                    'status' => 'Declined',
                    $remarksField => $request->approval_remarks,
                    $approvedByField => $user_id
                ]);

                Alert::success('TO has been declined.')->persistent('Dismiss');
                return back();
            }

        public function disapproveToAll(Request $request)
            {
                $ids = json_decode($request->ids, true);
                $user_id = auth()->id();
                $count = 0;

                if (!is_array($ids) || count($ids) === 0) {
                    return 'error';
                }

                foreach ($ids as $id) {
                    $employee_to = EmployeeTo::find($id);
                    if (!$employee_to) {
                        continue;
                    }

                    $final_approver_id = EmployeeApprover::where('user_id', $employee_to->user_id)
                        ->where('as_final', 'on')
                        ->value('approver_id');

                    $remarksField = $user_id == $final_approver_id ? 'approval_remarks2' : 'approval_remarks';
                    $approvedByField = $user_id == $final_approver_id ? 'approved_by_head_division' : 'approved_by';

                    $employee_to->update([
                        'status' => 'Declined',
                        $remarksField => 'Declined',
                        $approvedByField => $user_id
                    ]);

                    $count++;
                }
                return $count;
            }

        private function generateAdNumber()
            {
                $latestAd = PayInstruction::whereNotNull('ad_number')
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$latestAd || !$latestAd->ad_number) {
                    return 'AD-00001';
                }

                $latestNumber = preg_replace('/[^0-9]/', '', $latestAd->ad_number);
                $nextNumber = intval($latestNumber) + 1;

                return 'AD-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }

            public function getNextAdNumber()
            {
                return response()->json([
                    'ad_number' => $this->generateAdNumber()
                ]);
            }

    public function form_ad_approval(Request $request)
        {
            $today = date('Y-m-d');
            $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
            $to_date = $request->to ?? date('Y-m-d');
            $limit = $request->limit ?? 10;
            $user = auth()->user();

            $approvalThreshold = ApprovalByAmount::orderBy('higher_than', 'desc')->first();

            $ad_approvers = \App\ApproverSetting::with(['user.employee'])
                ->where('type_of_form', 'ad')
                ->where('status', 'Active')
                ->get();

            $is_approver = $ad_approvers->contains(function ($approver) use ($user) {
                return $approver->user_id == $user->id;
            });

            $filter_status = $request->status ?? 'Pending';

            $ads = PayInstruction::with(['user'])
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date);

            if ($filter_status !== 'All') {
                $ads->where('status', $filter_status);
            } else {
                $ads->where('status', '!=', 'Cancelled');
            }

            $ads = $ads->orderBy('created_at', 'DESC')->paginate($limit);

            $ads->getCollection()->transform(function ($ad) use ($approvalThreshold, $ad_approvers, $user) {
                $totalAmount = $ad->amount ?? 0;
                $ad->show_final_approver = false;
                $ad->final_approver = null;
                $ad->assigned_approvers = collect();
                
                // Check if first approver (level 1) has approved
                // Level 1 = First approver has approved
                // Level 2 = Final approver has approved (or ready for final approval)
                $ad->first_approver_approved = ($ad->level >= 1 && $ad->status != 'Cancelled');

                $approverEmployees = $ad_approvers->map(function ($approver) {
                    return $approver->user->employee ?? null;
                })->filter()->sortBy('level');

                $firstApprover = $approverEmployees->where('level', 1)->first();
                
                $finalApprover = $approverEmployees->whereIn('level', [2, 3])
                                ->sortBy('level')
                                ->first();

                if ($approvalThreshold && $totalAmount > $approvalThreshold->higher_than) {
                    // High amount requires both first approver and final approver
                    $assignedApprovers = collect([$firstApprover, $finalApprover])->filter();
                    
                    if ($finalApprover) {
                        $ad->final_approver = [
                            'id' => $finalApprover->id,
                            'name' => $finalApprover->first_name . ' ' . $finalApprover->last_name,
                            'position' => $finalApprover->position,
                            'level' => $finalApprover->level,
                            'employee_number' => $finalApprover->employee_number
                        ];
                        $ad->show_final_approver = ($ad->level == 1 && $ad->status == 'Pending');
                    }
                } else {
                    $assignedApprovers = collect([$firstApprover])->filter();
                }

                $ad->assigned_approvers = $assignedApprovers->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'name' => $employee->first_name . ' ' . $employee->last_name,
                        'position' => $employee->position,
                        'level' => $employee->level,
                        'employee_number' => $employee->employee_number,
                        'is_first_approver' => $employee->level == 1,
                        'is_final_approver' => in_array($employee->level, [2, 3])                      
                    ];
                })->unique('id');

                $ad->can_first_approve = false;
                $ad->can_final_approve = false;
                
                if ($user && $user->employee) {
                    if ($firstApprover && $firstApprover->id == $user->employee->id && $ad->level == 0 && $ad->status == 'Pending') {
                        $ad->can_first_approve = true;
                    }
                    
                    if ($finalApprover && $finalApprover->id == $user->employee->id && $ad->level == 1 && $ad->status == 'Pending' && $ad->show_final_approver) {
                        $ad->can_final_approve = true;
                    }
                }
                return $ad;
            });

            $for_approval = PayInstruction::where('status', 'Pending')
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->count();

            $approved = PayInstruction::where('status', 'Approved')
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->count();

            $declined = PayInstruction::where('status', 'Declined')
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->count();

            $employees = Employee::select('id', 'employee_number', 'first_name', 'last_name', 'employee_code', 'position', 'department_id', 'location', 'personal_email', 'level')
                ->with('department')
                ->where('status', 'Active')
                ->whereNotNull('employee_number')
                ->where('employee_number', '!=', '')
                ->orderBy('level', 'ASC')
                ->orderBy('employee_number', 'ASC')
                ->get();

            session(['pending_ad_count' => $for_approval]);

            $nextAdNumber = $this->generateAdNumber();
            
            return view('for-approval.ads_approval', [
                'header' => 'for-approval',
                'ads' => $ads,
                'employees' => $employees,
                'for_approval' => $for_approval,
                'approved' => $approved,
                'declined' => $declined,
                'from' => $from_date,
                'to' => $to_date,
                'status' => $filter_status,
                'limit' => $limit,
                'adNumber' => $nextAdNumber,
                'is_approver' => $is_approver,
                'ad_approvers' => $ad_approvers,
            ]);
        }

        public function approveAd(Request $request, $id)
        {
            $employee_ad = PayInstruction::find($id);

            if (!$employee_ad) {
                Alert::error('Pay Instruction not found.')->persistent('Dismiss');
                return back();
            }

            $current_user = auth()->user();
            $current_user_id = $current_user->id;

            $approvalThreshold = ApprovalByAmount::orderBy('higher_than', 'desc')->first();
            $thresholdAmount = $approvalThreshold ? $approvalThreshold->higher_than : 0;

            $ad_approvers = \App\ApproverSetting::with(['user.employee'])
                ->where('type_of_form', 'ad')
                ->where('status', 'Active')
                ->get();

            $approverEmployees = $ad_approvers->map(function ($approver) {
                return $approver->user->employee ?? null;
            })->filter()->sortBy('level');

            $firstApprover = $approverEmployees->where('level', 1)->first();
            
            $finalApprover = $approverEmployees->whereIn('level', [2, 3])
                ->sortBy('level')
                ->first();

            $current_user_employee = $current_user->employee;
            if (!$current_user_employee) {
                Alert::error('Employee record not found for current user.')->persistent('Dismiss');
                return back();
            }

            $is_first_approver = $firstApprover && $firstApprover->id === $current_user_employee->id;
            $is_final_approver = $finalApprover && $finalApprover->id === $current_user_employee->id;

            if (!$is_first_approver && !$is_final_approver) {
                Alert::error('You are not in the approval flow.')->persistent('Dismiss');
                return back();
            }

            $totalAmount = $employee_ad->amount ?? 0;
            $amount_over_threshold = $approvalThreshold && $totalAmount > $thresholdAmount;

            if (!$amount_over_threshold) {
                if ($is_first_approver) {
                    $employee_ad->approval_date = now();
                    $employee_ad->status = 'Approved';
                    $employee_ad->remarks = $request->approval_remarks;
                    $employee_ad->approved_by = $current_user_id;
                    $employee_ad->level = 1;
                    $employee_ad->save();
                } else {
                    Alert::error('Only the first approver can approve Pay Instructions under the threshold.')->persistent('Dismiss');
                    return back();
                }
            } else {
                // Over threshold: requires both level 1 and final approver (level 2/3)
                if ($is_first_approver) {
                    $employee_ad->approval_date = now();
                    $employee_ad->remarks = $request->approval_remarks;
                    $employee_ad->approved_by = $current_user_id;
                    $employee_ad->level = 1;
                    $employee_ad->status = 'Pending';
                    $employee_ad->save();
                } elseif ($is_final_approver) {
                    if ($employee_ad->level < 1) {
                        Alert::error('This Pay Instruction must be approved by the first approver (Level 1) before final approval.')->persistent('Dismiss');
                        return back();
                    }
                    $employee_ad->approval_date = now();
                    $employee_ad->status = 'Approved';
                    $employee_ad->remarks = $request->approval_remarks;
                    $employee_ad->approved_head_division = $current_user_id;
                    $employee_ad->level = $current_user_employee->level;
                    $employee_ad->save();
                } else {
                    Alert::error('You are not allowed to approve this Pay Instruction.')->persistent('Dismiss');
                    return back();
                }
            }

            Alert::success('Pay Instruction has been approved.')->persistent('Dismiss');
            return back();
        }

        public function declineAd(Request $request, $id)
        {
            $employee_ad = PayInstruction::find($id);

            if (!$employee_ad) {
                Alert::error('Pay Instruction not found.')->persistent('Dismiss');
                return back();
            }

            $current_user = auth()->user();

            $ad_approvers = \App\ApproverSetting::with(['user.employee'])
                ->where('type_of_form', 'ad')
                ->where('status', 'Active')
                ->get();

            $is_approver = $ad_approvers->contains(function ($approver) use ($current_user) {
                return $approver->user_id == $current_user->id;
            });

            if (!$is_approver) {
                Alert::error('You are not authorized to decline this Pay Instruction.')->persistent('Dismiss');
                return back();
            }

            $employee_ad->approval_date = now();
            $employee_ad->status = 'Declined';
            $employee_ad->remarks = $request->approval_remarks;
            $employee_ad->approved_by = $current_user->id;
            $employee_ad->level = 1;
            $employee_ad->save();

            try {
                Mail::to($employee_ad->requestor_email)->send(
                    new AdStatusNotification($employee_ad, $current_user, 'Declined')
                );

                Alert::success('Pay Instruction has been declined and notification sent to employee.')->persistent('Dismiss');
            } catch (\Exception $e) {
                \Log::error('Failed to send Pay Instruction decline email: ' . $e->getMessage());
                Alert::success('Pay Instruction has been declined, but failed to send email notification.')->persistent('Dismiss');
            }

            return back();
        }

        public function approveAdAll(Request $request)
        {
            $current_user = auth()->user();

            $ids = json_decode($request->ids, true);
            $count = 0;
            $errors = [];
            $current_user_id = $current_user->id;

            $approvalThreshold = ApprovalByAmount::orderBy('higher_than', 'desc')->first();
            $thresholdAmount = $approvalThreshold ? $approvalThreshold->higher_than : 0;

            $ad_approvers = \App\ApproverSetting::with(['user.employee'])
                ->where('type_of_form', 'ad')
                ->where('status', 'Active')
                ->get();

            $approverEmployees = $ad_approvers->map(function ($approver) {
                return $approver->user->employee ?? null;
            })->filter()->sortBy('level');

            $firstApprover = $approverEmployees->where('level', 1)->first();
            
            $finalApprover = $approverEmployees->whereIn('level', [2, 3])
                ->whereIn('position', ['MANAGER', 'SUPERVISOR'])
                ->sortBy('level')
                ->first();

            $current_user_employee = $current_user->employee;
            if (!$current_user_employee) {
                return response()->json(['error' => 'Employee record not found for current user.'], 400);
            }

            $is_first_approver = $firstApprover && $firstApprover->id === $current_user_employee->id;
            $is_final_approver = $finalApprover && $finalApprover->id === $current_user_employee->id;

            if (!$is_first_approver && !$is_final_approver) {
                return response()->json(['error' => 'You are not in the approval flow.'], 403);
            }

            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $employee_ad = PayInstruction::find($id);

                    if (!$employee_ad) {
                        $errors[] = "Pay Instruction ID {$id} not found.";
                        continue;
                    }

                    $totalAmount = $employee_ad->amount ?? 0;
                    $amount_over_threshold = $approvalThreshold && $totalAmount > $thresholdAmount;

                    if (!$amount_over_threshold) {
                        if ($is_first_approver) {
                            $employee_ad->approval_date = now();
                            $employee_ad->status = 'Approved';
                            $employee_ad->remarks = $request->approval_remarks ?? 'Bulk Approved';
                            $employee_ad->approved_by = $current_user_id;
                            $employee_ad->level = 1;
                            $employee_ad->save();
                            $count++;
                        } else {
                            $errors[] = "Only the first approver (Level 1) can approve Pay Instruction ID {$id} (under threshold).";
                        }
                    } else {
                        // Over threshold: requires both level 1 and final approver (level 2/3)
                        if ($is_first_approver) {
                            $employee_ad->approval_date = now();
                            $employee_ad->remarks = $request->approval_remarks ?? 'Bulk Approved - Level 1';
                            $employee_ad->approved_by = $current_user_id;
                            $employee_ad->level = 1;
                            $employee_ad->status = 'Pending';
                            $employee_ad->save();
                            $count++;
                        } elseif ($is_final_approver) {
                            if ($employee_ad->level < 1) {
                                $errors[] = "Pay Instruction ID {$id} must be approved by the first approver (Level 1) before final approval.";
                                continue;
                            }
                            
                            $employee_ad->approved_head_division = now();
                            $employee_ad->status = 'Approved';
                            $employee_ad->remarks = $request->approval_remarks ?? 'Bulk Approved - Final';
                            $employee_ad->approved_by = $current_user_id;
                            $employee_ad->level = $current_user_employee->level;
                            $employee_ad->save();
                            $count++;
                        } else {
                            $errors[] = "You are not allowed to approve Pay Instruction ID {$id} (over threshold).";
                        }
                    }
                }

                $response = ['count' => $count];
                if (!empty($errors)) {
                    $response['errors'] = $errors;
                }
                
                return response()->json($response);
            }

            return response()->json(['error' => 'No valid IDs provided.'], 400);
        }

        public function disapproveAdAll(Request $request)
        {
            $current_user = auth()->user();

            $ids = json_decode($request->ids, true);
            $count = 0;
            $approver_id = $current_user->id;

            $ad_approvers = \App\ApproverSetting::with(['user.employee'])
                ->where('type_of_form', 'ad')
                ->where('status', 'Active')
                ->get();

            $is_approver = $ad_approvers->contains(function ($approver) use ($current_user) {
                return $approver->user_id == $current_user->id;
            });

            if (!$is_approver) {
                return response()->json(['error' => 'You are not authorized to bulk-decline Pay Instructions.'], 403);
            }

            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $employee_ad = PayInstruction::find($id);

                    if ($employee_ad) {
                        $employee_ad->approval_date = now();
                        $employee_ad->status = 'Declined';
                        $employee_ad->remarks = $request->approval_remarks ?? 'Bulk Declined';
                        $employee_ad->approved_by = $approver_id;
                        $employee_ad->level = 1;
                        $employee_ad->save();
                        $count++;
                    }
                }

                return $count;
            }

            return 'error';
        }

    public function form_pd_approval(Request $request)
        {
            $today = date('Y-m-d');
            $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
            $to_date = $request->to ?? date('Y-m-d');
            $limit = $request->limit ?? 10;
            $filter_status = $request->status ?? 'Pending';

            $user = auth()->user();
            $approver_id = $user->id;

            $pds = collect();
            $pds_all = collect();

            $is_pd_approver = \App\ApproverSetting::where('user_id', $approver_id)
                ->where('type_of_form', 'pd')
                ->where('status', 'Active')
                ->exists();

            if ($is_pd_approver) {
                $query = EmployeePd::with([
                        'user.employee.department'
                    ])
                    ->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date);

                if ($filter_status !== 'All') {
                    $query->where('status', $filter_status);
                } else {
                    $query->where('status', '!=', 'Cancelled');
                }

                $pds = $query->orderBy('created_at', 'DESC')->paginate($limit);
                
                $pds->appends($request->query());

                $pds_all = EmployeePd::whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date)
                    ->where('status', '!=', 'Cancelled')
                    ->get();
            }

            $pd_approvers = \App\ApproverSetting::with('user.employee')
                ->where('type_of_form', 'pd')
                ->where('status', 'Active')
                ->get();

            $pendingCount = $pds_all->where('status', 'Pending')->count();
            $approvedCount = $pds_all->where('status', 'Approved')->count();
            $declinedCount = $pds_all->whereIn('status', ['Declined', 'Cancelled'])->count();

            session(['pending_pd_count' => $pendingCount]);

            $getApproverForEmployee = function($employee) use ($pd_approvers) {
                return $pd_approvers->first();
            };

            return view('for-approval.pds_approval', [
                'header' => 'for-approval',
                'pds' => $pds,
                'pds_all' => $pds_all,
                'for_approval' => $pendingCount,
                'approved' => $approvedCount,
                'declined' => $declinedCount,
                'approver_id' => $approver_id,
                'user_role' => $is_pd_approver ? 'pd_approver' : null,
                'from' => $from_date,
                'to' => $to_date,
                'status' => $filter_status,
                'limit' => $limit,
                'has_payroll_privilege' => $is_pd_approver,
                'pd_approvers' => $pd_approvers,
                'getApproverForEmployee' => $getApproverForEmployee,
            ]);
        }

        public function approvePd(Request $request, $id)
        {
            $employee_pd = EmployeePd::find($id);

            if (!$employee_pd) {
                Alert::error('PD not found.')->persistent('Dismiss');
                return back();
            }

            $current_user = auth()->user();

            // Check if user is authorized approver for PD type forms
            $is_authorized_approver = \App\ApproverSetting::where('user_id', $current_user->id)
                ->where('type_of_form', 'pd')
                ->where('status', 'Active')
                ->exists();

            if (!$is_authorized_approver) {
                Alert::error('You do not have permission to approve PDs.')->persistent('Dismiss');
                return back();
            }

            $employee_pd->approved_date = now();
            $employee_pd->status = 'Approved';
            $employee_pd->approval_remarks = $request->approval_remarks;
            $employee_pd->approved_by = $current_user->id;
            $employee_pd->save();

            Alert::success('PD has been approved.')->persistent('Dismiss');
            return back();
        }

        public function declinePd(Request $request, $id)
        {
            $employee_pd = EmployeePd::find($id);

            if (!$employee_pd) {
                Alert::error('PD not found.')->persistent('Dismiss');
                return back();
            }

            $current_user = auth()->user();

            // Check if user is authorized approver for PD type forms
            $is_authorized_approver = \App\ApproverSetting::where('user_id', $current_user->id)
                ->where('type_of_form', 'pd')
                ->where('status', 'Active')
                ->exists();

            if (!$is_authorized_approver) {
                Alert::error('You do not have permission to decline PDs.')->persistent('Dismiss');
                return back();
            }

            $employee_pd->approved_date = now();
            $employee_pd->status = 'Declined';
            $employee_pd->approval_remarks = $request->approval_remarks;
            $employee_pd->approved_by = $current_user->id;
            $employee_pd->save();

            Alert::success('PD has been declined.')->persistent('Dismiss');
            return back();
        }

        public function approvePdAll(Request $request)
        {
            $current_user = auth()->user();

            // Check if user is authorized approver for PD type forms
            $is_authorized_approver = \App\ApproverSetting::where('user_id', $current_user->id)
                ->where('type_of_form', 'pd')
                ->where('status', 'Active')
                ->exists();

            if (!$is_authorized_approver) {
                return response()->json(['error' => 'You do not have permission to bulk-approve PDs.'], 403);
            }

            $ids = json_decode($request->ids, true);
            $count = 0;
            $approver_id = $current_user->id;

            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $employee_pd = EmployeePd::find($id);

                    if ($employee_pd) {
                        // Check if user is authorized approver for PD type forms
                        $hasApprovalRight = \App\ApproverSetting::where('user_id', $approver_id)
                            ->where('type_of_form', 'pd')
                            ->where('status', 'Active')
                            ->exists();

                        if ($hasApprovalRight) {
                            $employee_pd->update([
                                'approved_date' => now(),
                                'status' => 'Approved',
                                'approval_remarks' => $request->approval_remarks ?? 'Bulk Approved',
                                'approved_by' => $approver_id
                            ]);
                            $count++;
                        }
                    }
                }

                return $count;
            }

            return 'error';
        }

        public function disapprovePdAll(Request $request)
        {
            $current_user = auth()->user();

            // Check if user is authorized approver for PD type forms
            $is_authorized_approver = \App\ApproverSetting::where('user_id', $current_user->id)
                ->where('type_of_form', 'pd')
                ->where('status', 'Active')
                ->exists();

            if (!$is_authorized_approver) {
                return response()->json(['error' => 'You do not have permission to bulk-decline PDs.'], 403);
            }

            $ids = json_decode($request->ids, true);
            $count = 0;
            $approver_id = $current_user->id;

            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $employee_pd = EmployeePd::find($id);

                    if ($employee_pd) {
                        // Check if user is authorized approver for PD type forms
                        $hasApprovalRight = \App\ApproverSetting::where('user_id', $approver_id)
                            ->where('type_of_form', 'pd')
                            ->where('status', 'Active')
                            ->exists();

                        if ($hasApprovalRight) {
                            $employee_pd->update([
                                'status' => 'Declined',
                                'approval_remarks' => $request->approval_remarks ?? 'Bulk Declined',
                                'approved_by' => $approver_id
                            ]);
                            $count++;
                        }
                    }
                }

                return $count;
            }

            return 'error';
        }

    public function form_coe_approval(Request $request)
        {
            $today = date('Y-m-d');
            $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
            $to_date = $request->to ?? date('Y-m-d');
            $limit = $request->limit ?? 10;
            $filter_status = $request->status ?? 'Pending';

            $user = auth()->user();
            $approver_id = $user->id;

            $coes = collect();
            $coes_all = collect();

            $is_coe_approver = \App\ApproverSetting::where('user_id', $approver_id)
                ->where('type_of_form', 'coe')
                ->where('status', 'Active')
                ->exists();

            if ($is_coe_approver) {
                $query = EmployeeCoe::with([
                        'user.employee.department',
                        'approvedBy', // Relationship to show who approved
                    ])
                    ->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date);

                if ($filter_status !== 'All') {
                    $query->where('status', $filter_status);
                } else {
                    $query->where('status', '!=', 'Cancelled');
                }

                $coes = $query->orderBy('created_at', 'DESC')->paginate($limit);
                
                $coes->appends($request->query());

                $coes_all = EmployeeCoe::whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date)
                    ->where('status', '!=', 'Cancelled')
                    ->get();
            }

            // Get all COE approvers for display
            $coe_approvers = \App\ApproverSetting::with('user.employee.department')
                ->where('type_of_form', 'coe')
                ->where('status', 'Active')
                ->get();

            $pendingCount = $coes_all->where('status', 'Pending')->count();
            $approvedCount = $coes_all->where('status', 'Approved')->count();
            $declinedCount = $coes_all->whereIn('status', ['Declined', 'Cancelled'])->count();

            session(['pending_coe_count' => $pendingCount]);

            // Simple function to get approver for employee (same as PD system)
            $getApproverForEmployee = function($employee) use ($coe_approvers) {
                return $coe_approvers->first();
            };

            return view('for-approval.coe_approval', [
                'header' => 'for-approval',
                'coes' => $coes,
                'coes_all' => $coes_all,
                'for_approval' => $pendingCount,
                'approved' => $approvedCount,
                'declined' => $declinedCount,
                'approver_id' => $approver_id,
                'user_role' => $is_coe_approver ? 'coe_approver' : null,
                'from' => $from_date,
                'to' => $to_date,
                'status' => $filter_status,
                'limit' => $limit,
                'has_payroll_privilege' => $is_coe_approver,
                'coe_approvers' => $coe_approvers,
                'getApproverForEmployee' => $getApproverForEmployee, // Add this function to view
            ]);
        }

    public function approveCoe(Request $request, $id)
        {
            $employee_coe = EmployeeCoe::find($id);

            if (!$employee_coe) {
                Alert::error('COE not found.')->persistent('Dismiss');
                return back();
            }

            $current_user = auth()->user();


            $is_coe_approver = \App\ApproverSetting::where('user_id', $current_user->id)
                ->where('type_of_form', 'coe')
                ->where('status', 'Active')
                ->exists();

            if (!$is_coe_approver) {
                Alert::error('You do not have permission to approve COEs.')->persistent('Dismiss');
                return back();
            }

            $employee_coe->approved_date = now();
            $employee_coe->status = 'Approved';
            $employee_coe->approval_remarks = $request->approval_remarks;
            $employee_coe->approved_by = $current_user->id;
            $employee_coe->save();

            Alert::success('COE Request has been approved.')->persistent('Dismiss');
            return back();
        }

        public function declineCoe(Request $request, $id)
        {
            $employee_coe = EmployeeCoe::find($id);

            if (!$employee_coe) {
                Alert::error('COE not found.')->persistent('Dismiss');
                return back();
            }

            $current_user = auth()->user();


            $is_coe_approver = \App\ApproverSetting::where('user_id', $current_user->id)
                ->where('type_of_form', 'coe')
                ->where('status', 'Active')
                ->exists();

            if (!$is_coe_approver) {
                Alert::error('You do not have permission to decline COEs.')->persistent('Dismiss');
                return back();
            }

            $employee_coe->approved_date = now();
            $employee_coe->status = 'Declined';
            $employee_coe->approval_remarks = $request->approval_remarks;
            $employee_coe->approved_by = $current_user->id;
            $employee_coe->save();

            Alert::success('COE Request has been declined.')->persistent('Dismiss');
            return back();
        }

        public function approveCoeAll(Request $request)
        {
            $current_user = auth()->user();


            $is_coe_approver = \App\ApproverSetting::where('user_id', $current_user->id)
                ->where('type_of_form', 'coe')
                ->where('status', 'Active')
                ->exists();

            if (!$is_coe_approver) {
                return response()->json(['error' => 'You do not have permission to bulk-approve COEs.'], 403);
            }

            $ids = json_decode($request->ids, true);
            $count = 0;
            $approver_id = $current_user->id;

            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $employee_coe = EmployeeCoe::find($id);

                    if ($employee_coe) {
                        $employee_coe->update([
                            'approved_date' => now(),
                            'status' => 'Approved',
                            'approval_remarks' => $request->approval_remarks ?? 'Bulk Approved',
                            'approved_by' => $approver_id
                        ]);
                        $count++;
                    }
                }

                return $count;
            }

            return 'error';
        }

        public function disapproveCoeAll(Request $request)
        {
            $current_user = auth()->user();


            $is_coe_approver = \App\ApproverSetting::where('user_id', $current_user->id)
                ->where('type_of_form', 'coe')
                ->where('status', 'Active')
                ->exists();

            if (!$is_coe_approver) {
                return response()->json(['error' => 'You do not have permission to bulk-decline COEs.'], 403);
            }

            $ids = json_decode($request->ids, true);
            $count = 0;
            $approver_id = $current_user->id;

            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $employee_coe = EmployeeCoe::find($id);

                    if ($employee_coe) {
                        $employee_coe->update([
                            'approved_date' => now(),
                            'status' => 'Declined',
                            'approval_remarks' => $request->approval_remarks ?? 'Bulk Declined',
                            'approved_by' => $approver_id
                        ]);
                        $count++;
                    }
                }

                return $count;
            }

            return 'error';
        }

    public function form_ne_approval(Request $request)
    {
        $today = date('Y-m-d');
        $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $to_date = $request->to ?? date('Y-m-d');
        $limit = $request->limit ?? 10;
        $filter_status = $request->status ?? 'Pending';

        $user = auth()->user();
        $approver_id = $user->id;

        $nes = collect();
        $nes_all = collect();

        $is_ne_approver = \App\ApproverSetting::where('user_id', $approver_id)
            ->where('type_of_form', 'ne')
            ->where('status', 'Active')
            ->exists();

        if ($is_ne_approver) {
            $query = EmployeeNe::with([
                    'user.employee.department'
                ])
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date);

            if ($filter_status !== 'All') {
                $query->where('status', $filter_status);
            } else {
                $query->where('status', '!=', 'Cancelled');
            }

            $nes = $query->orderBy('created_at', 'DESC')->paginate($limit);
            
            $nes->appends($request->query());

            $nes_all = EmployeeNe::whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->where('status', '!=', 'Cancelled')
                ->get();
        }

        $ne_approvers = \App\ApproverSetting::with('user.employee')
            ->where('type_of_form', 'ne')
            ->where('status', 'Active')
            ->get();

        $pendingCount = $nes_all->where('status', 'Pending')->count();
        $approvedCount = $nes_all->where('status', 'Approved')->count();
        $declinedCount = $nes_all->whereIn('status', ['Declined', 'Cancelled'])->count();

        session(['pending_ne_count' => $pendingCount]);

        $getApproverForEmployee = function($employee) use ($ne_approvers) {
            return $ne_approvers->first();
        };

        return view('for-approval.nes_approval', [
            'header' => 'for-approval',
            'nes' => $nes,
            'nes_all' => $nes_all,
            'for_approval' => $pendingCount,
            'approved' => $approvedCount,
            'declined' => $declinedCount,
            'approver_id' => $approver_id,
            'user_role' => $is_ne_approver ? 'ne_approver' : null,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
            'limit' => $limit,
            'has_ne_approval_privilege' => $is_ne_approver,
            'ne_approvers' => $ne_approvers,
            'getApproverForEmployee' => $getApproverForEmployee,
        ]);
    }

    public function approveNe(Request $request, $id)
    {
        $employee_ne = EmployeeNe::find($id);

        if (!$employee_ne) {
            Alert::error('NE not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();

        // Check if user is authorized approver for NE type forms
        $is_authorized_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'ne')
            ->where('status', 'Active')
            ->exists();

        if (!$is_authorized_approver) {
            Alert::error('You do not have permission to approve NEs.')->persistent('Dismiss');
            return back();
        }

        $employee_ne->approved_date = now();
        $employee_ne->status = 'Approved';
        $employee_ne->approval_remarks = $request->approval_remarks;
        $employee_ne->approved_by = $current_user->id;
        $employee_ne->save();

        Alert::success('NE has been approved.')->persistent('Dismiss');
        return back();
    }

    public function declineNe(Request $request, $id)
    {
        $employee_ne = EmployeeNe::find($id);

        if (!$employee_ne) {
            Alert::error('NE not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();

        // Check if user is authorized approver for NE type forms
        $is_authorized_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'ne')
            ->where('status', 'Active')
            ->exists();

        if (!$is_authorized_approver) {
            Alert::error('You do not have permission to decline NEs.')->persistent('Dismiss');
            return back();
        }

        $employee_ne->approved_date = now();
        $employee_ne->status = 'Declined';
        $employee_ne->approval_remarks = $request->approval_remarks;
        $employee_ne->approved_by = $current_user->id;
        $employee_ne->save();

        Alert::success('NE has been declined.')->persistent('Dismiss');
        return back();
    }

    public function approveNeAll(Request $request)
    {
        $current_user = auth()->user();

        // Check if user is authorized approver for NE type forms
        $is_authorized_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'ne')
            ->where('status', 'Active')
            ->exists();

        if (!$is_authorized_approver) {
            return response()->json(['error' => 'You do not have permission to bulk-approve NEs.'], 403);
        }

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_ne = EmployeeNe::find($id);

                if ($employee_ne) {
                    // Check if user is authorized approver for NE type forms
                    $hasApprovalRight = \App\ApproverSetting::where('user_id', $approver_id)
                        ->where('type_of_form', 'ne')
                        ->where('status', 'Active')
                        ->exists();

                    if ($hasApprovalRight) {
                        $employee_ne->update([
                            'approved_date' => now(),
                            'status' => 'Approved',
                            'approval_remarks' => $request->approval_remarks ?? 'Bulk Approved',
                            'approved_by' => $approver_id
                        ]);
                        $count++;
                    }
                }
            }

            return $count;
        }

        return 'error';
    }

    public function disapproveNeAll(Request $request)
    {
        $current_user = auth()->user();

        // Check if user is authorized approver for NE type forms
        $is_authorized_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'ne')
            ->where('status', 'Active')
            ->exists();

        if (!$is_authorized_approver) {
            return response()->json(['error' => 'You do not have permission to bulk-decline NEs.'], 403);
        }

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_ne = EmployeeNe::find($id);

                if ($employee_ne) {
                    // Check if user is authorized approver for NE type forms
                    $hasApprovalRight = \App\ApproverSetting::where('user_id', $approver_id)
                        ->where('type_of_form', 'ne')
                        ->where('status', 'Active')
                        ->exists();

                    if ($hasApprovalRight) {
                        $employee_ne->update([
                            'approved_date' => now(),
                            'status' => 'Declined',
                            'approval_remarks' => $request->approval_remarks ?? 'Bulk Declined',
                            'approved_by' => $approver_id
                        ]);
                        $count++;
                    }
                }
            }

            return $count;
        }

        return 'error';
    }

    public function form_dtr_approval(Request $request)
    { 
        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-3 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $dtrs = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();
        
        $user_ids = EmployeeApprover::select('user_id')->where('approver_id',$approver_id)->pluck('user_id')->toArray();

        $for_approval = EmployeeDtr::whereIn('user_id',$user_ids)
                                ->where('status','Pending')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();
        $approved = EmployeeDtr::whereIn('user_id',$user_ids)
                                ->where('status','Approved')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();
        $declined = EmployeeDtr::whereIn('user_id',$user_ids)
                                ->where('status','Declined')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();
        
        session(['pending_dtr_count'=>$for_approval]);

        return view('for-approval.dtr-approval',
        array(
            'header' => 'for-approval',
            'dtrs' => $dtrs,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
        ));

    }

    public function approveDtr(Request $request,$id){
        $employee_dtr = EmployeeDtr::where('id', $id)->first();
        if($employee_dtr){
            if($employee_dtr->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_dtr->user_id)
                                                    ->where('approver_id', auth()->user()->id)
                                                    ->first();
                
                $total_approvers = EmployeeApprover::where('user_id', $employee_dtr->user_id)->count();
                
                if($employee_approver->as_final == 'on' || $total_approvers == 1){
                    EmployeeDtr::Where('id', $id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                    ]);
                } else {
                    EmployeeDtr::Where('id', $id)->update([
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1
                    ]);
                }
            }
            else if($employee_dtr->level == 1){
                EmployeeDtr::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                ]);
            }
            Alert::success('DTR has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineDtr(Request $request,$id){
        EmployeeDtr::Where('id', $id)->update([
                        'status' => 'Declined',
                        'approval_remarks' => $request->approval_remarks,
                    ]);
        Alert::success('DTR has been declined.')->persistent('Dismiss');
        return back();
    }

    public function approveDtrAll(Request $request){
        
        $ids = json_decode($request->ids,true);

        $count = 0;
        if(count($ids) > 0){
            
            foreach($ids as $id){
                $employee_dtr = EmployeeDtr::where('id', $id)->first();
                if($employee_dtr){
                    $level = '';
                    $employee_approver = EmployeeApprover::where('user_id', $employee_dtr->user_id)->where('approver_id', auth()->user()->id)->first();
                    if($employee_dtr->level == 0){
                        if($employee_approver->as_final == 'on'){
                            EmployeeDtr::Where('id', $id)->update([
                                'approved_date' => date('Y-m-d'),
                                'status' => 'Approved',
                                'approval_remarks' => 'Approved',
                                'level' => 1,
                            ]);
                            $count++;
                            $employee_data = EmployeeDtr::with('employee')->findOrfail($id);
                            $attendance = new AttendanceLog;
                            $attendance->emp_code = $employee_data->employee->employee_code;
                            $attendance->date = date('Y-m-d',strtotime($employee_data->dtr_date));
                            $attendance->location = "DTR Correction";
                            $attendance->ip_address ="DTR Correction";
                            if($employee_data->time_in)
                            {
                                $attendance->date = date('Y-m-d',strtotime($employee_data->dtr_date));
                                $attendance->datetime = $employee_data->time_in;
                                $attendance->type = "0";
                                $attendance->save();
                            }
                            if($employee_data->time_out)
                            {
                                $attendance->datetime = $employee_data->time_out;
                                $attendance->type = "1";
                                $attendance->save();
                            }
                            $this->syncAttendance($employee_data->dtr_date,$employee_data->employee->employee_code);
                        }else{
                            EmployeeDtr::Where('id', $id)->update([
                                'approval_remarks' => 'Approved',
                                'level' => 1
                            ]);
                            $count++;
                        }
                    }
                    else if($employee_dtr->level == 1){
                        if($employee_approver->as_final == 'on'){
                            EmployeeDtr::Where('id', $id)->update([
                                'approved_date' => date('Y-m-d'),
                                'status' => 'Approved',
                                'approval_remarks' => 'Approved',
                                'level' => 2,
                            ]);
                            $count++;
                        }
                    }
                }
            }

            return $count;

        }else{
            return 'error';
        }
    }

    public function disapproveDtrAll(Request $request){
        
        $ids = json_decode($request->ids,true);

        $count = 0;
        if(count($ids) > 0){
            
            foreach($ids as $id){
                EmployeeDtr::Where('id', $id)->update([
                    'status' => 'Declined',
                    'approval_remarks' => 'Declined',
                ]);

                $count++;
            }

            return $count;

        }else{
            return 'error';
        }
    }

     public function syncAttendance($date,$emp_code)
    {
        // dd($request->all());
        
        $attendanceLogs = AttendanceLog::where('date', $date)
            ->where('emp_code', $emp_code)
            ->orderBy('datetime','asc')
            ->get();

            if ($attendanceLogs != null) 
            {
                foreach($attendanceLogs as $att)
                {
                    if ($att->type == 0)
                    {
                        $attend = Attendance::where('employee_code', $att->emp_code)->where('time_in', date('Y-m-d H:i:s', strtotime($att->datetime)))->first();
                        
                        if($attend == null)
                        {
                            $attendance = new Attendance;
                            $attendance->employee_code  = $att->emp_code;   
                            $attendance->time_in = date('Y-m-d H:i:s',strtotime($att->datetime));
                            $attendance->device_in = $att->location ." - ".$att->ip_address;
                            // $attendance->last_id = $att->id;
                            $attendance->save();
                        }
                    }
                    else 
                    {
                        $time_in_after = date('Y-m-d H:i:s',strtotime($att->datetime));
                        $time_in_before = date('Y-m-d H:i:s', strtotime ( '-23 hour' , strtotime ( $time_in_after ) )) ;
                        
                        $update = [
                            'time_out' =>  date('Y-m-d H:i:s', strtotime($att->datetime)),
                            'device_out' => $att->location ." - ".$att->ip_address,
                            // 'last_id' =>$att->id,
                        ];
                    
                        $attendance_in = Attendance::where('employee_code',$att->emp_code)
                            ->whereBetween('time_in',[$time_in_before,$time_in_after])
                            ->first();
                        
                        Attendance::where('employee_code',(string)$att->emp_code)
                        ->whereBetween('time_in',[$time_in_before,$time_in_after])
                        ->update($update);
                        
                        if($attendance_in == null)
                        {
                            $attendance = new Attendance;
                            $attendance->employee_code  = $att->emp_code;   
                            $attendance->time_out = date('Y-m-d H:i:s', strtotime($att->datetime));
                            $attendance->device_out = $att->location ." - ".$att->ip_address;
                            // $attendance->last_id = $att->id;
                            $attendance->save(); 
                        }
                    }
                }

            }
            return 'success';
    }
}
