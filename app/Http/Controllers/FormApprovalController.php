<?php

namespace App\Http\Controllers;
use App\EmployeeLeave;
use App\EmployeeAd;
use App\EmployeePd;
use App\EmployeeWfh;
use App\EmployeeOvertime;
use App\EmployeeTo;
use App\EmployeeCoe;
use App\EmployeeNe;
use App\EmployeeDtr;
use App\ApprovalByAmount;
use App\EmployeeApprover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

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

            $amount_over_5k = $employee_to->totalamount_total > 5000;
            $final_approver_index = $approvers->search(function($a) {
                return $a->as_final === 'on';
            });

            //Less than 5k → only first non-final approver
            if (!$amount_over_5k) {
                if ($current_approver_index === 0 && $final_approver_index !== 0) {
                    $employee_to->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => $request->approval_remarks,
                        'level' => $current_level + 1,
                        'approved_by' => $current_user_id
                    ]);
                } else {
                    Alert::error('Only the first approver can approve TOs under 5k.')->persistent('Dismiss');
                }
                return back();
            }

            //Over 5k → must go through first and final approver
            if ($current_approver_index === 0 && $final_approver_index !== 0) {
                $employee_to->update([
                    'level' => $final_approver_index,
                    'approval_remarks' => $request->approval_remarks,
                    'approved_by' => $current_user_id,
                    'status' => 'Pending'
                ]);
            } elseif ($current_approver_index === $final_approver_index) {
                $employee_to->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approval_remarks' => $request->approval_remarks,
                    'level' => $current_level + 1,
                    'approved_by' => $current_user_id
                ]);
            } else {
                Alert::error('You are not allowed to approve this TO.')->persistent('Dismiss');
            }

            Alert::success('TO has been approved.')->persistent('Dismiss');
            return back();
        }

        public function declineTo(Request $request, $id)
        {
            EmployeeTo::where('id', $id)->update([
                'status' => 'Declined',
                'approval_remarks' => $request->approval_remarks,
                'approved_by' => auth()->user()->id
            ]);

            Alert::success('TO has been declined.')->persistent('Dismiss');
            return back();
        }

        public function approveToAll(Request $request)
        {
            $ids = json_decode($request->ids, true);
            $count = 0;
            $current_user_id = auth()->user()->id;

            foreach ($ids as $id) {
                $employee_to = EmployeeTo::find($id);

                if (!$employee_to) continue;

                $approvers = EmployeeApprover::where('user_id', $employee_to->user_id)
                                    ->orderBy('id')
                                    ->get();

                $approver_ids = $approvers->pluck('approver_id')->toArray();
                $current_level = $employee_to->level;
                $current_approver_index = array_search($current_user_id, $approver_ids);

                if ($current_approver_index === false) continue;

                $amount_over_5k = $employee_to->totalamount_total > 5000;
                $final_approver_index = $approvers->search(function($a) {
                    return $a->as_final === 'on';
                });

                // Under 5k - only first non-final approver can approve
                if (!$amount_over_5k) {
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

                // Over 5k - must go through first and final approver
                if ($current_approver_index === 0 && $final_approver_index !== 0) {
                    $employee_to->update([
                        'level' => $final_approver_index,
                        'approval_remarks' => 'Approved',
                        'approved_by' => $current_user_id,
                        'status' => 'Pending'
                    ]);
                    $count++;
                } elseif ($current_approver_index === $final_approver_index) {
                    $employee_to->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => 'Approved',
                        'level' => $current_level + 1,
                        'approved_by' => $current_user_id
                    ]);
                    $count++;
                }
            }

            return $count;
        }


        public function disapproveToAll(Request $request)
        {
            $ids = json_decode($request->ids, true);
            $count = 0;

            if (count($ids) > 0) {
                foreach ($ids as $id) {
                    EmployeeTo::where('id', $id)->update([
                        'status' => 'Declined',
                        'approval_remarks' => 'Declined',
                        'approved_by' => auth()->user()->id
                    ]);
                    $count++;
                }
                return $count;
            } else {
                return 'error';
            }
        }

        public function form_ad_approval(Request $request)
            {
                $today = date('Y-m-d');
                $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
                $to_date = $request->to ?? date('Y-m-d');
                $limit = $request->limit ?? 10;
                $filter_status = $request->status ?? 'Pending';

                $user = auth()->user();
                $approver_id = $user->id;

                $has_payroll_privilege = $user->user_privilege && $user->user_privilege->payroll_view === 'on';

                $ads = collect();
                $ads_all = collect();

                if ($has_payroll_privilege) {
                    $query = EmployeeAd::with([
                            'approver' => function ($q) use ($approver_id) {
                                $q->with('approver_info')->where('approver_id', $approver_id);
                            },
                            'user.employee.department'
                        ])
                        ->whereHas('approver', function ($q) use ($approver_id) {
                            $q->where('approver_id', $approver_id);
                        })
                        ->whereDate('created_at', '>=', $from_date)
                        ->whereDate('created_at', '<=', $to_date);

                    if ($filter_status !== 'All') {
                        $query->where('status', $filter_status);
                    } else {
                        $query->where('status', '!=', 'Cancelled');
                    }
                    $ads = $query->orderBy('created_at', 'DESC')->paginate($limit);

                    $ads_all = EmployeeAd::whereHas('approver', function ($q) use ($approver_id) {
                            $q->where('approver_id', $approver_id);
                        })
                        ->whereDate('created_at', '>=', $from_date)
                        ->whereDate('created_at', '<=', $to_date)
                        ->where('status', '!=', 'Cancelled')
                        ->get();
                }

                $pendingCount = $ads_all->where('status', 'Pending')->count();
                $approvedCount = $ads_all->where('status', 'Approved')->count();
                $declinedCount = $ads_all->whereIn('status', ['Declined', 'Cancelled'])->count();

                session(['pending_ad_count' => $pendingCount]);

                return view('for-approval.ads_approval', [
                    'header' => 'for-approval',
                    'ads' => $ads,
                    'ads_all' => $ads_all,
                    'for_approval' => $pendingCount,
                    'approved' => $approvedCount,
                    'declined' => $declinedCount,
                    'approver_id' => $approver_id,
                    'user_role' => $has_payroll_privilege ? 'payroll' : null, // optional legacy compatibility
                    'from' => $from_date,
                    'to' => $to_date,
                    'status' => $filter_status,
                    'limit' => $limit,
                ]);
            }


        public function approveAd(Request $request, $id)
            {
                $employee_ad = EmployeeAd::find($id);

                if (!$employee_ad) {
                    Alert::error('AD not found.')->persistent('Dismiss');
                    return back();
                }

                $current_user = auth()->user();

                if (!$current_user->user_privilege || $current_user->user_privilege->payroll_view !== 'on') {
                    Alert::error('You do not have permission to approve ADs.')->persistent('Dismiss');
                    return back();
                }

                $employee_ad->update([
                    'approved_date' => now(),
                    'status' => 'Approved',
                    'approval_remarks' => $request->approval_remarks,
                    'approved_by' => $current_user->id
                ]);

                Alert::success('AD has been approved.')->persistent('Dismiss');
                return back();
            }


        public function declineAd(Request $request, $id)
            {
                $employee_ad = EmployeeAd::find($id);

                if (!$employee_ad) {
                    Alert::error('AD not found.')->persistent('Dismiss');
                    return back();
                }

                $current_user = auth()->user();

                if (!$current_user->user_privilege || $current_user->user_privilege->payroll_view !== 'on') {
                    Alert::error('You do not have permission to decline ADs.')->persistent('Dismiss');
                    return back();
                }

                $employee_ad->update([
                    'status' => 'Declined',
                    'approval_remarks' => $request->approval_remarks,
                    'approved_by' => $current_user->id
                ]);

                Alert::success('AD has been declined.')->persistent('Dismiss');
                return back();
            }


        public function approveAdAll(Request $request)
            {
                $current_user = auth()->user();

                if (!$current_user->user_privilege || $current_user->user_privilege->payroll_view !== 'on') {
                    return response()->json(['error' => 'You do not have permission to bulk-approve ADs.'], 403);
                }

                $ids = json_decode($request->ids, true);
                $count = 0;
                $approver_id = $current_user->id;

                if (!empty($ids)) {
                    foreach ($ids as $id) {
                        $employee_ad = EmployeeAd::find($id);

                        if ($employee_ad) {
                            $hasApprovalRight = $employee_ad->approver()
                                ->where('approver_id', $approver_id)
                                ->exists();

                            if ($hasApprovalRight) {
                                $employee_ad->update([
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


        public function disapproveAdAll(Request $request)
            {
                $current_user = auth()->user();

                if (!$current_user->user_privilege || $current_user->user_privilege->payroll_view !== 'on') {
                    return response()->json(['error' => 'You do not have permission to bulk-decline ADs.'], 403);
                }

                $ids = json_decode($request->ids, true);
                $count = 0;
                $approver_id = $current_user->id;

                if (!empty($ids)) {
                    foreach ($ids as $id) {
                        $employee_ad = EmployeeAd::find($id);

                        if ($employee_ad) {
                            $hasApprovalRight = $employee_ad->approver()
                                ->where('approver_id', $approver_id)
                                ->exists();

                            if ($hasApprovalRight) {
                                $employee_ad->update([
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

    public function form_pd_approval(Request $request)
        {
            $today = date('Y-m-d');
            $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
            $to_date = $request->to ?? date('Y-m-d');
            $limit = $request->limit ?? 10;
            $filter_status = $request->status ?? 'Pending';

            $user = auth()->user();
            $approver_id = $user->id;

            $has_payroll_privilege = $user->user_privilege && $user->user_privilege->payroll_view === 'on';

            $pds = collect();
            $pds_all = collect();

            if ($has_payroll_privilege) {
                $query = EmployeePd::with([
                        'approver' => function ($q) use ($approver_id) {
                            $q->with('approver_info')->where('approver_id', $approver_id);
                        },
                        'user.employee.department'
                    ])
                    ->whereHas('approver', function ($q) use ($approver_id) {
                        $q->where('approver_id', $approver_id);
                    })
                    ->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date);

                if ($filter_status !== 'All') {
                    $query->where('status', $filter_status);
                } else {
                    $query->where('status', '!=', 'Cancelled');
                }

                $pds = $query->orderBy('created_at', 'DESC')->paginate($limit);
                
                $pds->appends($request->query());

                $pds_all = EmployeePd::whereHas('approver', function ($q) use ($approver_id) {
                        $q->where('approver_id', $approver_id);
                    })
                    ->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date)
                    ->where('status', '!=', 'Cancelled')
                    ->get();
            }

            $pendingCount = $pds_all->where('status', 'Pending')->count();
            $approvedCount = $pds_all->where('status', 'Approved')->count();
            $declinedCount = $pds_all->whereIn('status', ['Declined', 'Cancelled'])->count();

            session(['pending_pd_count' => $pendingCount]);

            return view('for-approval.pds_approval', [
                'header' => 'for-approval',
                'pds' => $pds,
                'pds_all' => $pds_all,
                'for_approval' => $pendingCount,
                'approved' => $approvedCount,
                'declined' => $declinedCount,
                'approver_id' => $approver_id,
                'user_role' => $has_payroll_privilege ? 'payroll' : null,
                'from' => $from_date,
                'to' => $to_date,
                'status' => $filter_status,
                'limit' => $limit,
                'has_payroll_privilege' => $has_payroll_privilege,
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

            if (!$current_user->user_privilege || $current_user->user_privilege->payroll_view !== 'on') {
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

            if (!$current_user->user_privilege || $current_user->user_privilege->payroll_view !== 'on') {
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

                if (!$current_user->user_privilege || $current_user->user_privilege->payroll_view !== 'on') {
                    return response()->json(['error' => 'You do not have permission to bulk-approve PDs.'], 403);
                }

                $ids = json_decode($request->ids, true);
                $count = 0;
                $approver_id = $current_user->id;

                if (!empty($ids)) {
                    foreach ($ids as $id) {
                        $employee_pd = EmployeePd::find($id);

                        if ($employee_pd) {
                            $hasApprovalRight = $employee_pd->approver()
                                ->where('approver_id', $approver_id)
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

                if (!$current_user->user_privilege || $current_user->user_privilege->payroll_view !== 'on') {
                    return response()->json(['error' => 'You do not have permission to bulk-decline PDs.'], 403);
                }

                $ids = json_decode($request->ids, true);
                $count = 0;
                $approver_id = $current_user->id;

                if (!empty($ids)) {
                    foreach ($ids as $id) {
                        $employee_pd = EmployeePd::find($id);

                        if ($employee_pd) {
                            $hasApprovalRight = $employee_pd->approver()
                                ->where('approver_id', $approver_id)
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
        
        $has_coe_approval_privilege = $user->user_privilege && $user->user_privilege->reports_coe === 'on';
        
        $coes = collect();
        $coes_all = collect();

        if ($has_coe_approval_privilege) {
                $query = EmployeeCoe::with([
                        'approver' => function ($q) use ($approver_id) {
                            $q->with('approver_info')->where('approver_id', $approver_id);
                        },
                        'user.employee.department'
                    ])
                    ->whereHas('approver', function ($q) use ($approver_id) {
                        $q->where('approver_id', $approver_id);
                    })
                    ->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date);

                if ($filter_status !== 'All') {
                    $query->where('status', $filter_status);
                } else {
                    $query->where('status', '!=', 'Cancelled');
                }

                $coes = $query->orderBy('created_at', 'DESC')->paginate($limit);
                
                $coes->appends($request->query());

                $coes_all = EmployeeCoe::whereHas('approver', function ($q) use ($approver_id) {
                        $q->where('approver_id', $approver_id);
                    })
                    ->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date)
                    ->where('status', '!=', 'Cancelled')
                    ->get();
            }

        $pendingCount = $coes_all->where('status', 'Pending')->count();
        $approvedCount = $coes_all->where('status', 'Approved')->count();
        $declinedCount = $coes_all->whereIn('status', ['Declined', 'Cancelled'])->count();

        session(['pending_coe_count' => $pendingCount]);

        return view('for-approval.coe_approval', [
            'header' => 'for-approval',
            'coes' => $coes,
            'coes_all' => $coes_all,
            'for_approval' => $pendingCount,
            'approved' => $approvedCount,
            'declined' => $declinedCount,
            'approver_id' => $approver_id,
            'has_coe_approval_privilege' => $has_coe_approval_privilege,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
            'limit' => $limit,
        ]);
    }

    public function approveCoe(Request $request, $id)
        {
            $employee_coe = EmployeeCoe::find($id);

            if (!$employee_coe) {
                Alert::error('PD not found.')->persistent('Dismiss');
                return back();
            }

            $current_user = auth()->user();

            if (!$current_user->user_privilege || $current_user->user_privilege->reports_coe !== 'on') {
                Alert::error('You do not have permission to approve PDs.')->persistent('Dismiss');
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
                Alert::error('PD not found.')->persistent('Dismiss');
                return back();
            }

            $current_user = auth()->user();

            if (!$current_user->user_privilege || $current_user->user_privilege->reports_coe !== 'on') {
                Alert::error('You do not have permission to decline PDs.')->persistent('Dismiss');
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

        if (!$current_user->user_privilege || $current_user->user_privilege->reports_coe !== 'on') {
            return response()->json(['error' => 'You do not have permission to bulk-approve COEs.'], 403);
        }

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_coe = EmployeeCoe::find($id);

                if ($employee_coe) {
                    $hasApprovalRight = $employee_coe->approver()
                        ->where('approver_id', $approver_id)
                        ->exists();

                    if ($hasApprovalRight) {
                        $employee_coe->update([
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

    public function disapproveCoeAll(Request $request)
    {
        $current_user = auth()->user();

        if (!$current_user->user_privilege || $current_user->user_privilege->reports_coe !== 'on') {
            return response()->json(['error' => 'You do not have permission to bulk-decline COEs.'], 403);
        }

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_coe = EmployeeCoe::find($id);

                if ($employee_coe) {
                    $hasApprovalRight = $employee_coe->approver()
                        ->where('approver_id', $approver_id)
                        ->exists();

                    if ($hasApprovalRight) {
                        $employee_coe->update([
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

    public function form_ne_approval(Request $request)
    {
        $today = date('Y-m-d');
        $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $to_date = $request->to ?? date('Y-m-d');
        $limit = $request->limit ?? 10;
        $filter_status = $request->status ?? 'Pending';

        $user = auth()->user();
        $approver_id = $user->id;
        
        $has_ne_approval_privilege = $user->user_privilege && $user->user_privilege->reports_ne === 'on';
        
        $nes = collect();
        $nes_all = collect();

        if ($has_ne_approval_privilege) {
                $query = EmployeeNe::with([
                        'approver' => function ($q) use ($approver_id) {
                            $q->with('approver_info')->where('approver_id', $approver_id);
                        },
                        'user.employee.department'
                    ])
                    ->whereHas('approver', function ($q) use ($approver_id) {
                        $q->where('approver_id', $approver_id);
                    })
                    ->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date);

                if ($filter_status !== 'All') {
                    $query->where('status', $filter_status);
                } else {
                    $query->where('status', '!=', 'Cancelled');
                }

                $nes = $query->orderBy('created_at', 'DESC')->paginate($limit);
                
                $nes->appends($request->query());

                $nes_all = EmployeeNe::whereHas('approver', function ($q) use ($approver_id) {
                        $q->where('approver_id', $approver_id);
                    })
                    ->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date)
                    ->where('status', '!=', 'Cancelled')
                    ->get();
            }

        $pendingCount = $nes_all->where('status', 'Pending')->count();
        $approvedCount = $nes_all->where('status', 'Approved')->count();
        $declinedCount = $nes_all->whereIn('status', ['Declined', 'Cancelled'])->count();

        session(['pending_coe_count' => $pendingCount]);

        return view('for-approval.nes-approval', [
            'header' => 'for-approval',
            'nes' => $nes,
            'nes_all' => $nes_all,
            'for_approval' => $pendingCount,
            'approved' => $approvedCount,
            'declined' => $declinedCount,
            'approver_id' => $approver_id,
            'has_ne_approval_privilege' => $has_ne_approval_privilege,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
            'limit' => $limit,
        ]);
    }

    public function form_dtr_approval(Request $request)
    { 
        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
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
            $level = '';
            if($employee_dtr->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_dtr->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver->as_final == 'on'){
                    EmployeeDtr::Where('id', $id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                    ]);
                }else{
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
}