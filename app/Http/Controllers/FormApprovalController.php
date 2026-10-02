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
use App\EmployeeMta;
use App\ApprovalByAmount;
use App\AttendanceLog;
use App\Attendance;
use App\EmployeeApprover;
use App\EmployeeToApprovalRemark;
use Illuminate\Support\Facades\DB;
use App\IUR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AdStatusNotification;
use App\Mail\MtaDeclinedNotification;
use App\Mail\MtaApprovedNotification;
use App\Mail\ApprovedCoeMail;
use App\Mail\BmcMail;
use App\IUR_Accountability;
use App\MarketingCollateralBorrowing;
use App\PublicationRequest;
use App\LayoutDesign;
use App\Mail\LayoutDesignMail;
use App\Services\PublicationRequestWorkflowService;

class FormApprovalController extends Controller
{

    public function form_leave_approval (Request $request)
    {

        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $filter_request_to_cancel = '';
        if(isset($request->request_to_cancel)) {
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

        $chain_of_approvers = getAllChainApprovers($approver_id);
        
        $overtimes = EmployeeOvertime::with('approver.approver_info','user')
            ->where(function ($q) use ($chain_of_approvers, $approver_id, $filter_status) {
                if ($filter_status === 'Pending') {
                    $q->whereIn('user_id', $chain_of_approvers);
                } else {
                    $q->where('approved_by', $approver_id);
                }
            })
            ->where('status',$filter_status)
            ->whereDate('created_at','>=',$from_date)
            ->whereDate('created_at','<=',$to_date)
            ->orderBy('created_at','DESC')
            ->get();

        $for_approval = EmployeeOvertime::whereIn('user_id', $chain_of_approvers)
            ->where('status','Pending')
            ->whereDate('created_at','>=',$from_date)
            ->whereDate('created_at','<=',$to_date)
            ->count();

        $approved = EmployeeOvertime::where('approved_by', $approver_id)
            ->whereDate('created_at','>=',$from_date)
            ->whereDate('created_at','<=',$to_date)
            ->where('status','Approved')
            ->count();

        $declined = EmployeeOvertime::where('approved_by', $approver_id)
            ->whereDate('created_at','>=',$from_date)
            ->whereDate('created_at','<=',$to_date)
            ->where('status','Declined')
            ->count();

        session(['pending_overtime_count'=>$for_approval]);

        $employee_codes = Employee::whereIn('user_id', $overtimes->pluck('user_id'))
            ->pluck('employee_number', 'user_id');

        $attendances = collect();
        if ($employee_codes->isNotEmpty()) {
            $attendances = Attendance::whereIn('employee_code', $employee_codes->values())
                ->whereIn(DB::raw('DATE(time_in)'), $overtimes->pluck('ot_date')->toArray())
                ->select('employee_code', 'time_in', 'time_out', DB::raw('DATE(time_in) as att_date'))
                ->get()
                ->groupBy('employee_code');
        }

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
            'employee_codes' => $employee_codes,
            'attendances' => $attendances,
        ));

    }

    public function approveOvertime(Request $request, EmployeeOvertime $employee_overtime) {
        if($employee_overtime){
            $level = '';

            $rendered_hours = null;
            $emp = Employee::where('user_id', $employee_overtime->user_id)->first();

            if ($emp) {
                $attendance = Attendance::where('employee_code', $emp->employee_number)
                    ->whereDate('time_in', $employee_overtime->ot_date)
                    ->whereNotNull('time_out')
                    ->first();

                if ($attendance) {
                    $otStart = new \DateTime($employee_overtime->start_time);
                    $otEnd   = new \DateTime($attendance->time_out);

                    if ($otEnd > $otStart) {
                        $diff = $otStart->diff($otEnd);
                        $rendered_hours = round(($diff->days * 24 + $diff->h + $diff->i / 60), 2);
                    }
                }
            }


            if($employee_overtime->level == 0) {

                $all_user_ids = getAllChainApprovers(auth()->user()->id);

                $ot_approved_hrs = $request->ot_approved_hrs;
                $break_hrs = (float) $request->break_hrs ?: 0;

                // automatic apply 1 hour break
                if ((float) $ot_approved_hrs >= 9 && $break_hrs < 1) {
                    $break_hrs = 1;
                }

                // validation
                if ($rendered_hours !== null && (float) $ot_approved_hrs > $rendered_hours) {
                    Alert::error('Approve hours (' . $ot_approved_hrs . ') must not exceed rendered hours (' . $rendered_hours . ' hour(s)). ')->persistent('Dismiss');
                    return back();
                }

                if (in_array($employee_overtime->user_id, $all_user_ids)) {
                    EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                        'break_hrs' => $break_hrs,
                        'ot_approved_hrs' => $ot_approved_hrs,
                        'approved_by' => auth()->user()->id
                    ]);
                }

            }
            else if($employee_overtime->level == 1){
                $ot_approved_hrs = $request->ot_approved_hrs;
                $break_hrs = (float) $request->break_hrs ?: 0;

                if ((float) $ot_approved_hrs >= 9 && $break_hrs < 1) {
                    $break_hrs = 1;
                }

                if ($rendered_hours !== null && (float) $ot_approved_hrs > $rendered_hours) {
                    Alert::error('Approve hours (' . $ot_approved_hrs . ') must not exceed rendered hours (' . $rendered_hours . ' hour(s)). ')->persistent('Dismiss');
                    return back();
                }

                EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                    'break_hrs' => $break_hrs,
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
            $rendered_hrs = null;
            $emp = Employee::where('user_id', $employee_overtime->user_id)->first();

            if ($emp) {
                $attendance = Attendance::where('employee_code', $emp->employee_number)
                    ->whereDate('time_in', $employee_overtime->ot_date)
                    ->whereNotNull('time_out')
                    ->first();
                if ($attendance) {
                    $otStart = new \DateTime($employee_overtime->start_time);
                    $otEnd   = new \DateTime($attendance->time_out);
                    if ($otEnd > $otStart) {
                        $diff = $otStart->diff($otEnd);
                        $rendered_hrs = round(($diff->days * 24 + $diff->h + $diff->i / 60), 2);
                    }
                }
            }
            $ot_approved_hrs = $request->ot_approved_hrs;
            $break_hrs = (float) $request->break_hrs ?: 0;

            /* add 1h break if the overtime is 9 hours long */
            if ((float) $ot_approved_hrs >= 9 && $break_hrs < 1) {
                $break_hrs = 1;
            }

            if ($rendered_hrs !== null && (float) $ot_approved_hrs > $rendered_hrs) {
                Alert::error('Approved hours (' . $ot_approved_hrs . ') must not exceed rendered hours (' . $rendered_hrs . ' hrs).')->persistent('Dismiss');
                return back();
            }

            EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                'approval_remarks' => $request->approval_remarks,
                'break_hrs' => $break_hrs,
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

        $tos = EmployeeTo::with([
                'approver.approver_info',
                'user.employee.department',
                'approvedBy',
                'approvedByHeadDivision',
                'approvalRemarks.approver'
            ])
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

            $approvers = $approvers->sortBy('level')->values();

            $lastApprover = $approvers->last();
            if ($lastApprover) {
                $to->final_approver = $lastApprover->approver_info;
            }

            if ($approvalThreshold && $totalAmount > $approvalThreshold->higher_than) {
                $finalApprover = $approvers->where('as_final', 'on')->first();

                if ($finalApprover) {
                    $to->approver = $approvers->filter(function($a) use ($finalApprover) {
                        return $a->level <= $finalApprover->level;
                    })->values();

                    $to->final_approver = $finalApprover->approver_info;
                    $to->show_final_approver = true;
                } else {
                    $to->approver = $approvers;
                }
            } else {
                $firstApprover = $approvers->sortBy('level')->first();
                $to->approver = collect([$firstApprover])->filter()->values();
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
                                ->orderBy('level')
                                ->get();

            $current_level = $employee_to->level;

            $current_approver = $approvers->firstWhere('approver_id', $current_user_id);

            if (!$current_approver) {
                Alert::error('You are not in the approval flow.')->persistent('Dismiss');
                return back();
            }

            $current_approver_level = $current_approver->level;

            if ($current_level != $current_approver_level) {
                Alert::error('This TO is not at your approval level.')->persistent('Dismiss');
                return back();
            }

            $amount_over_threshold = $employee_to->totalamount_total > $thresholdAmount;

            $final_approver = $approvers->firstWhere('as_final', 'on');
            $is_final_approver = $final_approver && $final_approver->approver_id == $current_user_id;

            EmployeeToApprovalRemark::updateOrCreate(
                [
                    'employee_to_id' => $employee_to->id,
                    'approver_id' => $current_user_id,
                    'level' => $current_level
                ],
                [
                    'action' => 'Approved',
                    'remarks' => $request->approval_remarks ?? 'Approved',
                    'action_date' => now()
                ]
            );

            if (!$amount_over_threshold) {
                $first_approver = $approvers->sortBy('level')->first();

                if ($current_user_id == $first_approver->approver_id) {
                    $employee_to->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'level' => $current_level + 1,
                        'approved_by' => $current_user_id
                    ]);
                    Alert::success('TO has been approved.')->persistent('Dismiss');
                } else {
                    Alert::error('Only the first approver can approve TOs under the threshold.')->persistent('Dismiss');
                }
                return back();
            }

            if ($is_final_approver) {
                $employee_to->update([
                    'approved_head_division' => date('Y-m-d'),
                    'status' => 'Approved',
                    'level' => $current_level + 1,
                    'approved_by_head_division' => $current_user_id
                ]);
            } else {
                $employee_to->update([
                    'approved_date' => date('Y-m-d'),
                    'level' => $current_level + 1,
                    'approved_by' => $current_user_id,
                    'status' => 'Pending'
                ]);
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
                                ->orderBy('level')
                                ->get();

                $current_level = $employee_to->level;

                $current_approver = $approvers->firstWhere('approver_id', $current_user_id);

                if (!$current_approver || $current_level != $current_approver->level) {
                    continue;
                }

                $amount_over_threshold = $employee_to->totalamount_total > $thresholdAmount;

                $final_approver = $approvers->firstWhere('as_final', 'on');
                $is_final_approver = $final_approver && $final_approver->approver_id == $current_user_id;

                if (!$amount_over_threshold) {
                    $first_approver = $approvers->sortBy('level')->first();

                    if ($current_user_id == $first_approver->approver_id) {
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

                if ($is_final_approver) {
                    $employee_to->update([
                        'approved_head_division' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks2' => 'Approved',
                        'level' => $current_level + 1,
                        'approved_by_head_division' => $current_user_id
                    ]);
                    $count++;
                } else {
                    $employee_to->update([
                        'approved_date' => date('Y-m-d'),
                        'level' => $current_level + 1,
                        'approval_remarks' => 'Approved',
                        'approved_by' => $current_user_id,
                        'status' => 'Pending'
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

            $current_level = $employee_to->level;

            EmployeeToApprovalRemark::updateOrCreate(
                [
                    'employee_to_id' => $employee_to->id,
                    'approver_id' => $user_id,
                    'level' => $current_level
                ],
                [
                    'action' => 'Declined',
                    'remarks' => $request->approval_remarks ?? 'Declined',
                    'action_date' => now()
                ]
            );

            $final_approver_id = EmployeeApprover::where('user_id', $employee_to->user_id)
                ->where('as_final', 'on')
                ->value('approver_id');

            $approvedByField = $user_id == $final_approver_id ? 'approved_by_head_division' : 'approved_by';

            $employee_to->update([
                'status' => 'Declined',
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

                $current_level = $employee_to->level;

                EmployeeToApprovalRemark::updateOrCreate(
                    [
                        'employee_to_id' => $employee_to->id,
                        'approver_id' => $user_id,
                        'level' => $current_level
                    ],
                    [
                        'action' => 'Declined',
                        'remarks' => 'Declined',
                        'action_date' => now()
                    ]
                );

                $final_approver_id = EmployeeApprover::where('user_id', $employee_to->user_id)
                    ->where('as_final', 'on')
                    ->value('approver_id');

                $approvedByField = $user_id == $final_approver_id ? 'approved_by_head_division' : 'approved_by';

                $employee_to->update([
                    'status' => 'Declined',
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
            $receivedCount = $pds_all->where('status', 'Approved')->count();
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
                'received' => $receivedCount,
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

    public function form_coe_approval(Request $request) {
        $today = date('Y-m-d');
        $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $to_date = $request->to ?? date('Y-m-d');
        $limit = $request->limit ?? 10;
        $filter_status = $request->status ?? 'All';

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

            $coes = $query->orderByRaw("FIELD(status, 'Approved', 'Pending', 'Declined', 'Cancelled') ASC")
                          ->orderBy('created_at', 'DESC')
                          ->paginate($limit);

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
        $processingCount = $coes_all->where('status', 'Processing')->count();

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
            'processing' => $processingCount,
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
            'getApproverForEmployee' => $getApproverForEmployee,
        ]);

    }

    // for coe
    public function uploadProofDelivery(Request $request, $id) {
        $employee_coe = EmployeeCoe::find($id);

        $request->validate([
            'proof_of_delivery' => 'required'
        ]);

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
            Alert::error('You do not have privilege to attach file.')->persistent('Dismiss');
            return back();
        }

        $uploadPath = 'uploads/coe_proof_of_delivery';
        if (!\File::exists(public_path($uploadPath))) {
            \File::makeDirectory(public_path($uploadPath), 0755, true);
        }

        $file = $request->file('proof_of_delivery');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($uploadPath), $filename);

        $employee_coe->proof = $uploadPath . '/' . $filename;
        $employee_coe->save();

        Alert::success('Proof of Delivery uploaded.')->persistent('Dismiss');
        return back();
    }

    public function uploadCoeAttachment(Request $request, $id) {
        $employee_coe = EmployeeCoe::find($id);

        $request->validate([
            'attachment' => 'required|mimes:jpg,jpeg,png|max:4096'
        ]);

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
            Alert::error('You do not have permission to attach files to COEs.')->persistent('Dismiss');
            return back();
        }

        $uploadPath = 'uploads/coe_attachments';
        if (!\File::exists(public_path($uploadPath))) {
            \File::makeDirectory(public_path($uploadPath), 0755, true);
        }

        $file = $request->file('attachment');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($uploadPath), $filename);

        if ($employee_coe->attachment && file_exists(public_path($employee_coe->attachment))) {
            @unlink(public_path($employee_coe->attachment));
        }

        $employee_coe->attachment = $uploadPath . '/' . $filename;
        $employee_coe->save();

        Alert::success('Attachment uploaded successfully.')->persistent('Dismiss');
        return back();
    }

    public function approveCoe(Request $request, $id) {
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

        if ($employee_coe->status !== 'Processing') {
            Alert::error('COE must be in Processing status before approval.')->persistent('Dismiss');
            return back();
        }

        $needsFile = in_array($employee_coe->receive_method, ['Viber', 'Email']);
        if ($needsFile && !$employee_coe->attachment && !$request->hasFile('attachment')) {
            Alert::error('Please attach a file before approving this COE request.')->persistent('Dismiss');
            return back();
        }

        if ($needsFile && $request->hasFile('attachment')) {
            $request->validate(['attachment' => 'mimes:jpg,jpeg,png,pdf|max:4096']);
        }

        $employee_coe->approved_date = now();
        $employee_coe->status = 'Approved';
        $employee_coe->approval_remarks = $request->approval_remarks;
        $employee_coe->approved_by = $current_user->id;

        if (in_array($employee_coe->receive_method, ['Viber', 'Email']) && $request->hasFile('attachment')) {
            $uploadPath = 'uploads/coe_attachments';
            if (!\File::exists(public_path($uploadPath))) {
                \File::makeDirectory(public_path($uploadPath), 0755, true);
            }
            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($uploadPath), $filename);
            $employee_coe->attachment = $uploadPath . '/' . $filename;
        }
        $employee_coe->save();

        $recipientEmail = $employee_coe->email ?? optional($employee_coe->user)->email;

        $isHrHead = $current_user->employee && $current_user->employee->position === 'HR Head';

        if (in_array($employee_coe->receive_method, ['Email', 'Hard Copy']) && !$isHrHead) {
            Mail::to($recipientEmail)
                ->cc(['coe.request@pascalresources.com.ph'])
                ->send(new \App\Mail\ApprovedCoeMail($employee_coe));
        }

        Alert::success('COE Request has been approved.')->persistent('Dismiss');
        return back();
    }

    public function processCoe(Request $request, $id) {
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
            Alert::error('You do not have permission to process COEs.')->persistent('Dismiss');
            return back();
        }

        $employee_coe->status = 'Processing';
        $employee_coe->processed_at = now();
        $employee_coe->approval_remarks = $request->approval_remarks;
        $employee_coe->approved_by = $current_user->id;
        $employee_coe->save();

        // Send processing email to requestor
        $recipientEmail = $employee_coe->email ?? optional($employee_coe->user)->email;

        if ($recipientEmail) {
            Mail::to($recipientEmail)->send(new \App\Mail\ProcessingCoeMail($employee_coe));
        }

        Alert::success('COE Request is now being processed.')->persistent('Dismiss');
        return back();
    }

    public function declineCoe(Request $request, $id) {
        $employee_coe = EmployeeCoe::find($id);

        if (!$employee_coe) {
            Alert::error('COE not found.')->persistent('Dismiss');
            return back();
        }

        $validator = \Validator::make($request->all(), [
            'approval_remarks' => 'required|string|max:200',
        ], [
            'approval_remarks.required' => 'Please provide a reason for declining the COE request.',
            'approval_remarks.max' => 'Remarks must not exceed 200 characters.',
        ]);

        if ($validator->fails()) {
            Alert::error($validator->errors()->first())->persistent('Dismiss');
            return back()->withErrors($validator);
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

        if (in_array($employee_coe->status, ['Processing', 'Approved'])) {
            Alert::error('Cannot decline. Request is already ' . $employee_coe->status . '.')->persistent('Dismiss');
            return back();
        }

        $employee_coe->approved_date = now();
        $employee_coe->status = 'Declined';
        $employee_coe->approval_remarks = $request->approval_remarks;
        $employee_coe->approved_by = $current_user->id;
        $employee_coe->save();

        // Send decline email to requestor
        $recipientEmail = $employee_coe->email ?? optional($employee_coe->user)->email;
        if ($recipientEmail) {
            $data = [
                'coe_id' => $employee_coe->id,
                'coe_reference' => $employee_coe->reference_number,
                'name' => $employee_coe->first_name . ' ' . $employee_coe->last_name,
                'purpose' => $employee_coe->purpose,
                'reason_for_request' => $employee_coe->reason_for_request,
                'designation' => $employee_coe->designation,
                'approval_remarks' => $employee_coe->approval_remarks,
            ];
            
            $isHrHead = $current_user->employee && $current_user->employee->position === 'HR Head';

            if (!$isHrHead) {
                Mail::to($recipientEmail)->send(new \App\Mail\DeclinedCoeMail($data));
            }
        }

        Alert::success('COE Request has been declined.')->persistent('Dismiss');
        return back();
    }

    public function resendCoeEmail(Request $request, $id) {
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
            Alert::error('You do not have permission to resend COE emails.')->persistent('Dismiss');
            return back();
        }

        $request->validate(['email' => 'required|email']);

        if (!$employee_coe->attachment || !file_exists(public_path($employee_coe->attachment))) {
            Alert::error('No attachment found for this COE.')->persistent('Dismiss');
            return back();
        }

        $recipientEmail = $request->email;

        Mail::to($recipientEmail)
            ->cc(['coe.request@pascalresources.com.ph'])
            ->send(new \App\Mail\ApprovedCoeMail($employee_coe));

        Alert::success('COE email resent to ' . $recipientEmail . '.')->persistent('Dismiss');
        return back();
    }

    /* unused method */
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

    // ID and Uniform Request Approval
    public function form_iur_approval(Request $request) {
        $today = date('Y-m-d');
        $from_date = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $to_date = $request->to ?? date('Y-m-d');
        $limit = $request->limit ?? 10;
        $filter_status = $request->status ?? 'Pending';

        $user = auth()->user();
        $approver_id = $user->id;

        $iurs = collect();
        $iur_all = collect();

        $is_iur_approver = \App\ApproverSetting::with('user.employee')
            ->where('user_id', $approver_id)
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->exists();
        
        if (!$is_iur_approver) {
            Alert::error('You do not have privilege to access this page.')->persistent('Dismiss');
            return redirect('/');
        }

        // Determine which location group this approver handles
        $approverLocation = optional($user->employee)->location ?? '';
        $handlesLbGb = str_contains($approverLocation, 'Lubao Office');

        if ($is_iur_approver) {
            $query = IUR::with([
                    'user.contact_person',
                    'approvedBy',
                ])
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date);

            if ($filter_status !== 'All') {
                $query->where('status', $filter_status);
            } else {
                $query->where('status', '!=', 'Cancelled');
            }

            // Filter by location based on approver
            if ($handlesLbGb) {
                $query->where('work_location', 'Plant');
            } else {
                $query->where('work_location', '!=', 'Plant');
            }

            $iurs = $query->orderBy('created_at', 'DESC')->paginate($limit);
            $iurs->appends($request->query());

            $iur_all_query = IUR::whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->where('status', '!=', 'Cancelled');

            if ($handlesLbGb) {
                $iur_all_query->where('work_location', 'Plant');
            } else {
                $iur_all_query->where('work_location', '!=', 'Plant');
            }
            $iur_all = $iur_all_query->get();
        }

        // Get all COE approvers for display
        $coe_approvers = \App\ApproverSetting::with('user.employee.department')
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->get();

        $pendingCount = $iur_all->where('status', 'Pending')->count();
        $partialReceiveCount = $iur_all->where('status', 'Partial')->count();
        $processingCount = $iur_all->where('status', 'Processing')->count();
        $receivedCount = $iur_all->where('status', 'Released')->count();
        $declinedCount = $iur_all->whereIn('status', ['Declined', 'Cancelled'])->count();

        session(['pending_coe_count' => $pendingCount]);

        // Match approver to employee based on work_location
        $getApproverForEmployee = function($employee, $workLocation = '') use ($coe_approvers) {
            $isPlant = in_array($workLocation, ['Guinobatan', 'Lubao']);
            foreach ($coe_approvers as $approver) {
                $apprLocation = optional($approver->user->employee)->location ?? '';
                $apprPlant = str_contains($apprLocation, 'Plant');
                if ($isPlant === $apprPlant) {
                    return $approver;
                }
            }
            return $coe_approvers->first();
        };

        return view('for-approval.iur_approval', [
            'header' => 'for-approval',
            'iurs' => $iurs,
            'iur_all' => $iur_all,
            'for_approval' => $pendingCount,
            'partial_receive' => $partialReceiveCount,
            'processing' => $processingCount,
            'received' => $receivedCount,
            'declined' => $declinedCount,
            'approver_id' => $approver_id,
            'user_role' => $is_iur_approver ? 'coe_approver' : null,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
            'limit' => $limit,
            'has_payroll_privilege' => $is_iur_approver,
            'coe_approvers' => $coe_approvers,
            'getApproverForEmployee' => $getApproverForEmployee, // Add this function to view
        ]);
    }

    public function processIur(Request $request, $id) {
        $employee_iur = IUR::find($id);

        if (!$employee_iur) {
            Alert::error('IUR not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();

        $is_iur_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->exists();

        if (!$is_iur_approver) {
            Alert::error('You do not have privilege.')->persistent('Dismiss');
            return back();
        }

        // Save approved quantities before processing
        if ($request->has('prod_qty')) $employee_iur->prod_qty = (int) $request->prod_qty;
        if ($request->has('white_qty')) $employee_iur->white_qty = (int) $request->white_qty;
        if ($request->has('black_qty')) $employee_iur->black_qty = (int) $request->black_qty;
        if ($request->has('collar_qty')) $employee_iur->collar_qty = (int) $request->collar_qty;

        $employee_iur->approved_date = now();
        $employee_iur->status = $request->status;
        $employee_iur->approval_remarks = $request->approval_remarks;
        $employee_iur->approved_by = $current_user->id;
        $employee_iur->save();

        // Send email to requestor if Processing
        if ($request->status === 'Processing') {
            try {
                Mail::to($employee_iur->user->email)->send(
                    new \App\Mail\IurRequestMail($employee_iur, 'Processing')
                );
            } catch (\Exception $e) {
                \Log::warning('IUR processing email failed: ' . $e->getMessage());
            }
        }

        Alert::success('Request has been processed.')->persistent('Dismiss');
        return back();
    }

    // partial approve for uniform selection
    public function partialApprove(Request $request, $id) {
        $employee_iur = IUR::find($id);

        if (!$employee_iur) {
            Alert::error('Request not found.')->persistent('Dismiss');
        }

        $current_user = auth()->user();

        $is_iur_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->exists();

        if (!$is_iur_approver) {
            Alert::error('You do not have permission to change the status.')->persistent('Dismiss');
            return back();
        }

        $employee_iur->approved_date = now();
        $employee_iur->status = 'Partial';
        $employee_iur->approval_remarks = $request->approval_remarks;
        $employee_iur->approved_by = $current_user->id;
        $employee_iur->save();

        Alert::success('IUR Request has been partially processed.')->persistent('Dismiss');
        return back();
    }

    // receive IUR status
    public function receiveIur(Request $request, $id) {
        $employee_iur = IUR::findOrFail($id);
        $current_user = auth()->user();

        $is_iur_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->exists();

        if (!$is_iur_approver) {
            Alert::error('You do not have permission.')->persistent('Dismiss');
            return back();
        }

        $employee_iur->status = 'Released';
        $employee_iur->approval_remarks = $request->approval_remarks;
        $employee_iur->approved_by = $current_user->id;
        $employee_iur->save();

        Alert::success('IUR Request marked as received.')->persistent('Dismiss');
        return back();
    }

    public function declineIur(Request $request, $id) {
        $employee_iur = IUR::find($id);

        if (!$employee_iur) {
            Alert::error('IUR not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();


        $is_iur_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->exists();

        if (!$is_iur_approver) {
            Alert::error('You do not have permission to decline IURs.')->persistent('Dismiss');
            return back();
        }

        $employee_iur->approved_date = now();
        $employee_iur->status = 'Declined';
        $employee_iur->approval_remarks = $request->approval_remarks;
        $employee_iur->approved_by = $current_user->id;
        $employee_iur->save();

        // Send email to requestor
        try {
            Mail::to($employee_iur->user->email)->send(
                new \App\Mail\IurRequestMail($employee_iur, 'Declined', $request->approval_remarks)
            );
        } catch (\Exception $e) {
            \Log::warning('IUR declined email failed: ' . $e->getMessage());
        }

        Alert::success('IUR Request has been declined.')->persistent('Dismiss');
        return back();
    }
    
    public function releaseIur(Request $request, $id) {
        $employee_iur = IUR::findOrFail($id);
        $current_user = auth()->user();

        $is_iur_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->exists();

        if (!$is_iur_approver) {
            Alert::error('You do not have permission.')->persistent('Dismiss');
            return back();
        }

        // Create accountability record
        $acc = new \App\IUR_Accountability();
        $acc->iur_id = $employee_iur->id;
        $batchNum = $employee_iur->accountabilities()->count() + 1;
        $acc->accountability_ref = $employee_iur->iur_reference . '-' . $batchNum;
        $acc->released_prod_qty = $request->released_prod_qty ?? 0;
        $acc->released_white_qty = $request->released_white_qty ?? 0;
        $acc->released_black_qty = $request->released_black_qty ?? 0;
        $acc->released_collar_qty = $request->released_collar_qty ?? 0;
        $acc->released_id = $request->released_id ?? false;

        $acc->prod_condition = $request->prod_condition ?? null;
        $acc->white_condition = $request->white_condition ?? null;
        $acc->black_condition = $request->black_condition ?? null;
        $acc->collar_condition = $request->collar_condition ?? null;
        $acc->notes = $request->notes ?? null;

        $acc->issued_by = $current_user->id;
        $acc->issued_date = now();
        $acc->save();

        // Update cumulative totals
        $employee_iur->increment('received_prod_qty', $acc->released_prod_qty);
        $employee_iur->increment('received_white_qty', $acc->released_white_qty);
        $employee_iur->increment('received_black_qty', $acc->released_black_qty);
        $employee_iur->increment('received_collar_qty', $acc->released_collar_qty);

        // Check if all fulfilled
        $allFulfilled = true;
        if ($employee_iur->prod_qty && $employee_iur->received_prod_qty < $employee_iur->prod_qty) $allFulfilled = false;
        if ($employee_iur->white_qty && $employee_iur->received_white_qty < $employee_iur->white_qty) $allFulfilled = false;
        if ($employee_iur->black_qty && $employee_iur->received_black_qty < $employee_iur->black_qty) $allFulfilled = false;
        if ($employee_iur->collar_qty && $employee_iur->received_collar_qty < $employee_iur->collar_qty) $allFulfilled = false;

        $employee_iur->status = $allFulfilled ? 'Released' : 'Partial';
        $employee_iur->approval_remarks = $request->approval_remarks;
        $employee_iur->approved_by = $current_user->id;
        $employee_iur->save();

        Alert::success('Items released successfully. Accountability ref: ' . $acc->accountability_ref)->persistent('Dismiss');
        return back();
    }

    // mark ID as printed
    public function confirmIurIdsPrinted(Request $request) {
        $validated = $request->validate([
            'refs' => 'required|array|min:1',
            'refs.*' => 'required|string|distinct|exists:employee_iur,iur_reference',
        ]);

        $isIurApprover = \App\ApproverSetting::where(
            'user_id',
            auth()->id()
        )
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->exists();

        if (!$isIurApprover) {
            Alert::error(
                'You do not have privilege for this action'
            )->persistent('Dismiss');

            return redirect()->back();
        }

        $references = $validated['refs'];

        $updatedCount = \DB::transaction(function () use ($references) {
            $iurs = IUR::whereIn('iur_reference', $references)
                ->whereIn('request_for', ['ID', 'Both'])
                ->lockForUpdate()
                ->get();

            // Prevent a partial update if an invalid request was included.
            if ($iurs->count() !== count($references)) {
                return 0;
            }

            foreach ($iurs as $iur) {
                $iur->id_printed_at = now();
                $iur->id_print_count =
                    ((int) $iur->id_print_count) + 1;

                $iur->save();
            }

            return $iurs->count();
        });

        if ($updatedCount !== count($references)) {
            Alert::error(
                'One or more ID requests could not be confirmed.'
            )->persistent('Dismiss');

            return redirect('/batch-print-form');
        }

        Alert::success(
            $updatedCount . ' ID(s) marked as printed.'
        )->persistent('Dismiss');

        return redirect('/batch-print-form');
    }

    // update notes per accountability
    public function updateAccountabilityNote(Request $request, $id) {
        $accountability = \App\IUR_Accountability::findOrFail($id);
        $accountability->notes = $request->notes;
        $accountability->save();

        return response()->json(['success' => true]);
    }

    public function viewAccountability($id) {
        $acc = \App\IUR_Accountability::with(['iur.user.contact_person', 'issuer', 'iur.user.department'])->findOrFail($id);

        return view('for-approval.view-accountability', ['acc' => $acc]);
    }

    public function printAccountabilityTab($id) {
        $acc = \App\IUR_Accountability::with(['iur.user', 'issuer', 'iur.user.department'])->findOrFail($id);

        return view('for-approval.print-iur-accountability-tab', ['acc' => $acc]);
    }

    public function saveIurSignature(Request $request, $id)
    {
        $employee_iur = IUR::findOrFail($id);

        $request->validate([
            'signature' => 'required|string',
            'accountability_id' => 'nullable|integer|exists:iur_accountabilities,id',
        ]);

        $imageParts = explode(';base64,', $request->signature);
        $decoded = base64_decode($imageParts[1] ?? $request->signature);

        // Save to specific accountability
        if ($request->accountability_id) {
            $acc = IUR_Accountability::find($request->accountability_id);
            if ($acc && $acc->iur_id == $id) {
                $filename = 'sig_iur_' . $id . '_acc_' . $acc->id . '_' . time() . '_' . uniqid() . '.png';
                $uploadPath = public_path('signatures');

                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                file_put_contents($uploadPath . '/' . $filename, $decoded);

                // Delete old signature for this accountability
                if ($acc->employee_signature) {
                    $oldPath = public_path($acc->employee_signature);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $acc->employee_signature = 'signatures/' . $filename;
                $acc->signed_at = now();
                $acc->save();
            }
        } else {
            // Fallback to IUR-level signature (no accountability_id provided)
            $filename = 'sig_iur_' . $id . '_' . time() . '_' . uniqid() . '.png';
            $uploadPath = public_path('signatures');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            file_put_contents($uploadPath . '/' . $filename, $decoded);

            if ($employee_iur->e_signature) {
                $oldPath = public_path($employee_iur->e_signature);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $employee_iur->e_signature = 'signatures/' . $filename;
            $employee_iur->save();
        }

        return response()->json(['success' => true]);
    }

    // approver's signature
    public function saveApproverSignature(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json(['error' => 'Employee not found.'], 400);
        }

        $request->validate([
            'signature' => 'required|string',
        ]);

        $imageParts = explode(';base64,', $request->signature);
        $decoded = base64_decode($imageParts[1] ?? $request->signature);

        $filename = 'sig_emp_' . $employee->user_id . '_' . time() . '_' . uniqid() . '.png';
        $uploadPath = public_path('signatures');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        file_put_contents($uploadPath . '/' . $filename, $decoded);

        // Delete old approver signature file
        if ($employee->signature) {
            $oldPath = public_path($employee->signature);
            if (file_exists($oldPath)) unlink($oldPath);
        }

        $employee->signature = 'signatures/' . $filename;
        $employee->save();

        return response()->json(['success' => true]);
    }

    // unused method
    public function approveIurAll(Request $request)
    {
        $current_user = auth()->user();

        $is_iur_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->exists();

        if (!$is_iur_approver) {
            return response()->json(['error' => 'You do not have permission to bulk-approve IURs.'], 403);
        }

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_iur = IUR::find($id);

                if ($employee_iur) {
                    $employee_iur->update([
                        'approved_date' => now(),
                        'status' => 'Processing',
                        'approval_remarks' => $request->approval_remarks ?? 'Bulk Processed',
                        'approved_by' => $approver_id
                    ]);
                    $count++;
                }
            }

            return $count;
        }

        return 'error';
    }

    public function disapproveIurAll(Request $request)
    {
        $current_user = auth()->user();


        $is_iur_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->exists();

        if (!$is_iur_approver) {
            return response()->json(['error' => 'You do not have permission to bulk-decline IURs.'], 403);
        }

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_iur = IUR::find($id);

                if ($employee_iur) {
                    $employee_iur->update([
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


    public function form_ne_approval(Request $request) {
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
        $dtrs = EmployeeDtr::with('approver.approver_info','user.employee.department')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();
        // dd($dtrs);
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
                    $employee_data = EmployeeDtr::with('employee')->findOrfail($id);

                            if($employee_data->time_in != null)
                            {
                                 $attendance = new AttendanceLog;
                                $attendance->emp_code = $employee_data->employee->employee_code;
                                $attendance->date = date('Y-m-d',strtotime($employee_data->dtr_date));
                                $attendance->location = "DTR Correction";
                                $attendance->ip_address ="DTR Correction";
                                $attendance->date = date('Y-m-d',strtotime($employee_data->dtr_date));
                                $attendance->datetime = $employee_data->time_in;
                                $attendance->type = "0";
                                $attendance->save();
                            }
                            if($employee_data->time_out != null)
                            {
                                $attendance = new AttendanceLog;
                                $attendance->emp_code = $employee_data->employee->employee_code;
                                $attendance->date = date('Y-m-d',strtotime($employee_data->dtr_date));
                                $attendance->location = "DTR Correction";
                                $attendance->ip_address ="DTR Correction";
                                $attendance->datetime = $employee_data->time_out;
                                $attendance->type = "1";
                                $attendance->save();
                            }
                            $this->syncAttendance($employee_data->dtr_date,$employee_data->employee->employee_code);

                } else {
                    EmployeeDtr::Where('id', $id)->update([
                        'approval_remarks' => $request->approval_remarks,
                        'level' => $employee_approver->level+1,
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

    // public function approveDtrAll(Request $request){

    //     $ids = json_decode($request->ids,true);

    //     $count = 0;
    //     if(count($ids) > 0){

    //         foreach($ids as $id){
    //             $employee_dtr = EmployeeDtr::with('employee')->where('id', $id)->first();
    //             if($employee_dtr){
    //                 $level = '';
    //                 $employee_approver = EmployeeApprover::where('user_id', $employee_dtr->user_id)->where('approver_id', auth()->user()->id)->first();
    //                     //  dd($employee_approver);
    //                     if($employee_approver->as_final == 'on'){
    //                         $employee = Employee::where('user_id',$employee_dtr->user_id)->first();

    //                         EmployeeDtr::Where('id', $id)->update([
    //                             'approved_date' => date('Y-m-d'),
    //                             'status' => 'Approved',
    //                             'approval_remarks' => 'Approved',
    //                             'level' => 1,
    //                         ]);
    //                         $count++;

    //                         if($employee_dtr->time_in != null)
    //                         {
    //                              $attendance = new AttendanceLog;
    //                             $attendance->emp_code = $employee_dtr->employee->employee_code;
    //                             $attendance->date = date('Y-m-d',strtotime($employee_dtr->dtr_date));
    //                             $attendance->location = "DTR Correction";
    //                             $attendance->ip_address ="DTR Correction";
    //                             $attendance->date = date('Y-m-d',strtotime($employee_dtr->dtr_date));
    //                             $attendance->datetime = $employee_dtr->time_in;
    //                             $attendance->type = "0";
    //                             $attendance->save();
    //                         }
    //                         if($employee_dtr->time_out != null)
    //                         {
    //                             $attendance = new AttendanceLog;
    //                             $attendance->emp_code = $employee_dtr->employee->employee_code;
    //                             $attendance->date = date('Y-m-d',strtotime($employee_dtr->dtr_date));
    //                             $attendance->location = "DTR Correction";
    //                             $attendance->ip_address ="DTR Correction";
    //                             $attendance->datetime = $employee_dtr->time_out;
    //                             $attendance->type = "1";
    //                             $attendance->save();
    //                         }
    //                         $this->syncAttendance($employee_dtr->dtr_date,$employee_dtr->employee->employee_code);
    //                     }else{
    //                         EmployeeDtr::Where('id', $id)->update([
    //                             'approval_remarks' => 'Approved',
    //                             'level' => $employee_dtr->level+1
    //                         ]);
    //                         $count++;
    //                     }


    //             }
    //         }

    //         return $count;

    //     }else{
    //         return 'error';
    //     }
    // }
    public function approveDtrAll(Request $request)
    {
        try {

            $ids = json_decode($request->ids, true);

            if(empty($ids) || count($ids) == 0){

                return response()->json([
                    'success' => false,
                    'message' => 'No selected DTR found.'
                ]);
            }

            $count = 0;

            foreach($ids as $id){

                $employee_dtr = EmployeeDtr::with('employee')
                    ->where('id', $id)
                    ->first();

                if(!$employee_dtr){
                    continue;
                }

                $employee_approver = EmployeeApprover::where('user_id', $employee_dtr->user_id)
                    ->where('approver_id', auth()->user()->id)
                    ->first();

                if(!$employee_approver){
                    continue;
                }

                // FINAL APPROVER
                if($employee_approver->as_final == 'on'){

                    EmployeeDtr::where('id', $id)->update([
                        'approved_date' => now(),
                        'status' => 'Approved',
                        'approval_remarks' => 'Approved',
                        'level' => 1,
                    ]);

                    // TIME IN
                    if($employee_dtr->time_in){

                        AttendanceLog::create([
                            'emp_code' => $employee_dtr->employee->employee_code,
                            'date' => date('Y-m-d', strtotime($employee_dtr->dtr_date)),
                            'location' => 'DTR Correction',
                            'ip_address' => 'DTR Correction',
                            'datetime' => $employee_dtr->time_in,
                            'type' => '0'
                        ]);
                    }

                    // TIME OUT
                    if($employee_dtr->time_out){

                        AttendanceLog::create([
                            'emp_code' => $employee_dtr->employee->employee_code,
                            'date' => date('Y-m-d', strtotime($employee_dtr->dtr_date)),
                            'location' => 'DTR Correction',
                            'ip_address' => 'DTR Correction',
                            'datetime' => $employee_dtr->time_out,
                            'type' => '1'
                        ]);
                    }

                    // SYNC ATTENDANCE
                    $this->syncAttendance(
                        $employee_dtr->dtr_date,
                        $employee_dtr->employee->employee_code
                    );

                } else {

                    // NEXT LEVEL APPROVAL
                    EmployeeDtr::where('id', $id)->update([
                        'approval_remarks' => 'Approved',
                        'level' => $employee_dtr->level + 1
                    ]);
                }

                $count++;
            }

            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => 'DTR approved successfully.'
            ]);

        } catch (\Exception $e){

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
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

    // MTA Approval
    public function form_mta_approval(Request $request)
    {
        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-3 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $get_approvers = new EmployeeApproverController;
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        $mtas = EmployeeMta::with('approver.approver_info','user', 'approverMta')
                                // ->whereHas('approver',function($q) use($approver_id) {
                                //     $q->where('approver_id',$approver_id);
                                // })
                                ->whereHas('approverMta',function($q) use($approver_id) {
                                    $q->where('user_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();

        $user_ids = EmployeeMta::with('approver.approver_info','user', 'approverMta')
                                ->whereHas('approverMta',function($q) use($approver_id) {
                                    $q->where('user_id',$approver_id);
                                })->pluck('user_id')->toArray();

        $for_approval = EmployeeMta::whereIn('user_id',$user_ids)
                                ->where('status','Pending')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();

        $approved = EmployeeMta::whereIn('user_id',$user_ids)
                                ->where('status','Approved')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();
        $declined = EmployeeMta::whereIn('user_id',$user_ids)
                                ->where('status','Declined')
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();

        session(['pending_mta_count'=>$for_approval]);

        return view('for-approval.mta-approval',
        array(
            'header' => 'for-approval',
            'mtas' => $mtas,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
            'all_approvers' => $all_approvers,
        ));

    }

    // public function approveMta(Request $request,$id){
    //     $employee_mta = EmployeeMta::where('id', $id)->first();
    //     if($employee_mta){
    //             $employee_approver = EmployeeApprover::where('user_id', $employee_mta->user_id)
    //                                                 ->where('approver_id', auth()->user()->id)
    //                                                 ->first();

    //             $total_approvers = EmployeeApprover::where('user_id', $employee_mta->user_id)->count();

    //             if($employee_approver->as_final == 'on' || $total_approvers == 1){
    //                 EmployeeMta::Where('id', $id)->update([
    //                     'approved_date' => date('Y-m-d'),
    //                     'status' => 'Approved',
    //                     'approval_remarks' => $request->approval_remarks,
    //                     'approved_by' => auth()->user()->id,
    //                     'level' => 1,
    //                 ]);
    //                 $employee_data = EmployeeMta::with('employee')->findOrfail($id);
    //             } else {
    //                 EmployeeMta::Where('id', $id)->update([
    //                     'approval_remarks' => $request->approval_remarks,
    //                     'level' => $employee_approver->level+1,
    //                 ]);
    //             }

    //         Alert::success('Monetized Transportation Allowance has been approved.')->persistent('Dismiss');
    //         return back();
    //     }
    // }
    public function approveMta(Request $request, $id)
    {
        $employee_mta = EmployeeMta::find($id);

        if (!$employee_mta) {
            Alert::error('Monetized Transportation Allowance not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();


        $is_mta_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'mta')
            ->where('status', 'Active')
            ->exists();

        if (!$is_mta_approver) {
            Alert::error('You do not have permission to approve MTA requests.')->persistent('Dismiss');
            return back();
        }

        $employee_mta->approved_date = now();
        $employee_mta->status = 'Approved';
        $employee_mta->payment_status = 'Approved';
        $employee_mta->approval_remarks = $request->approval_remarks;
        $employee_mta->approved_by = $current_user->id;
        $employee_mta->save();

        try {
            if ($employee_mta->user && $employee_mta->user->email) {
                Mail::to($employee_mta->user->email)
                    ->send(new MtaApprovedNotification($employee_mta, $current_user));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send MTA approval email: ' . $e->getMessage());
        }

        Alert::success('Monetized Transportation Allowance has been approved.')->persistent('Dismiss');
        return back();
    }

    // public function declineMta(Request $request,$id){
    //     EmployeeMta::Where('id', $id)->update([
    //                     'status' => 'Declined',
    //                     'approval_remarks' => $request->approval_remarks,
    //                 ]);
    //     Alert::success('Monetized Transportation Allowance has been declined.')->persistent('Dismiss');
    //     return back();
    // }

    // public function approveMtaAll(Request $request)
    // {
    //     $ids = json_decode($request->ids,true);
    //     $count = 0;
    //     if(count($ids) > 0){
    //         foreach($ids as $id){
    //             $employee_mta = EmployeeMta::with('employee')->where('id', $id)->first();
    //             if($employee_mta){
    //                 $level = '';
    //                 $employee_approver = EmployeeApprover::where('user_id', $employee_mta->user_id)->where('approver_id', auth()->user()->id)->first();
    //                 if($employee_approver->as_final == 'on'){
    //                     $employee = Employee::where('user_id',$employee_mta->user_id)->first();

    //                     EmployeeMta::Where('id', $id)->update([
    //                         'approved_date' => date('Y-m-d'),
    //                         'status' => 'Approved',
    //                         'approval_remarks' => 'Approved',
    //                         'level' => 1,
    //                     ]);
    //                     $count++;
    //                 }else{
    //                     EmployeeMta::Where('id', $id)->update([
    //                         'approval_remarks' => 'Approved',
    //                         'level' => $employee_mta->level+1
    //                     ]);
    //                     $count++;
    //                 }
    //             }
    //         }
    //         return $count;

    //     }else{
    //         return 'error';
    //     }
    // }

    // public function disapproveMtaAll(Request $request){

    //     $ids = json_decode($request->ids,true);

    //     $count = 0;
    //     if(count($ids) > 0){

    //         foreach($ids as $id){
    //             EmployeeMta::Where('id', $id)->update([
    //                 'status' => 'Declined',
    //                 'approval_remarks' => 'Declined',
    //             ]);

    //             $count++;
    //         }

    //         return $count;

    //     }else{
    //         return 'error';
    //     }
    // }

    public function declineMta(Request $request, $id)
    {
        $employee_mta = EmployeeMta::with('user')->find($id);

        if (!$employee_mta) {
            Alert::error('Monetized Transportation Allowance not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();

        $is_mta_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'mta')
            ->where('status', 'Active')
            ->exists();

        if (!$is_mta_approver) {
            Alert::error('You do not have permission to decline Monetized Transportation Allowance requests.')->persistent('Dismiss');
            return back();
        }

        $employee_mta->approved_date = now();
        $employee_mta->status = 'Declined';
        $employee_mta->approval_remarks = $request->approval_remarks;
        $employee_mta->approved_by = $current_user->id;
        $employee_mta->save();

        try {
            if ($employee_mta->user && $employee_mta->user->email) {
                Mail::to($employee_mta->user->email)
                    ->send(new MtaDeclinedNotification($employee_mta, $current_user));
            }
        } catch (\Exception $e) {
                \Log::error('Failed to send MTA decline email: ' . $e->getMessage());
        }

        Alert::success('Monetized Transportation Allowance Request has been declined.')->persistent('Dismiss');
        return back();
    }

    public function approveMtaAll(Request $request)
    {
        $current_user = auth()->user();

        $is_mta_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'mta')
            ->where('status', 'Active')
            ->exists();

        if (!$is_mta_approver) {
            return response()->json(['error' => 'You do not have permission to bulk-approve Monetized Transportation Allowance requests.'], 403);
        }

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_mta = EmployeeMta::find($id);

                if ($employee_mta) {
                    $employee_mta->update([
                        'approved_date' => now(),
                        'status' => 'Approved',
                        'payment_status' => 'Approved',
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

    public function disapproveMtaAll(Request $request)
    {
        $current_user = auth()->user();

        $is_mta_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'mta')
            ->where('status', 'Active')
            ->exists();

        if (!$is_mta_approver) {
            return response()->json(['error' => 'You do not have permission to bulk-decline Monetized Transportation Allowance requests.'], 403);
        }

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_mta = EmployeeMta::find($id);

                if ($employee_mta) {
                    $employee_mta->update([
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

    public function formBmcApproval(Request $request) {
        $approver = auth()->user()->id;

        $isApprover = \App\ApproverSetting::where('user_id', $approver)
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->exists();

        if (!$isApprover) {
            Alert::error('You do not have permission to access this resource.')->persistent('Dismiss');
            return back();
        }

        $filter_status = $request->status ?? '';

        $query = MarketingCollateralBorrowing::with([
            'accountabilities.items',
            'accountabilities.releasedBy',
            'accountabilities.closedBy',
        ]);

        if ($filter_status === 'Declined / Cancelled') {
            $query->where('status', [
                MarketingCollateralBorrowing::STATUS_DECLINED,
                MarketingCollateralBorrowing::STATUS_CANCELLED
            ]);
        } elseif ($filter_status !== '') {
            $query->where('status', $filter_status);
        }

        // search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('borrower_last_name', 'like', "%{$search}%")
                  ->orWhere('event_name', 'like', "%{$search}%")
                  ->orWhere('hub_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $borrowings = $query->orderBy('created_at', 'DESC')->get();

        $for_approval = MarketingCollateralBorrowing::where('status', 'For Approval')->count();
        $approved = MarketingCollateralBorrowing::where('status', 'Approved')->count();
        $declined = MarketingCollateralBorrowing::where('status', 'Declined')->count();
        $cancelled = MarketingCollateralBorrowing::where('status', 'Cancelled')->count();

        return view('for-approval.bmc-approval',
            array(
                'header'        => 'for-approval',
                'borrowings'    => $borrowings,
                'filter_status' => $filter_status,
                'from'          => $request->from,
                'to'            => $request->to,
                'for_approval'  => $for_approval,
                'approved'      => $approved,
                'declined'      => $declined,
                'cancelled'     => $cancelled,
                'approver_id'   => $approver
            )
        );
    }

    public function showBmcApproval($id) {
        $approver = auth()->user()->id;

        $isApprover = \App\ApproverSetting::where('user_id', $approver)
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->exists();

        if (!$isApprover) {
            Alert::error('You do not have permission to access this resource.')->persistent('Dismiss');
            return back();
        }

        $borrowing = MarketingCollateralBorrowing::with([
            'accountabilities.items',
            'accountabilities.releasedBy',
            'accountabilities.closedBy',
        ])->findOrFail($id);

        return view('for-approval.bmc-approval-show', array(
            'header'   => 'for-approval',
            'borrowing' => $borrowing,
        ));
    }

    public function approveBmcRequest(Request $request, $id) {
        $borrowing = MarketingCollateralBorrowing::find($id);

         if (!$borrowing) {
             Alert::error('Borrowing request not found')->persistent('Dismiss');
             return back();
         }

        $current_user = auth()->user();

        $is_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->exists();

        if (!$is_approver) {
             Alert::error('You do not have privilege for this action')->persistent('Dismiss');
             return back();
        }

        $borrowing->status = 'Approved';
        $borrowing->processed_at = now();
        $borrowing->mbd_remarks = $request->mbd_remarks;
        $borrowing->processed_by = $current_user->id;
        $borrowing->save();

        try {
            if ($borrowing->email) {
                /* Mail::to($borrowing->email)->send(new BmcMail($borrowing, 'approved')); */
            }
        } catch (\Exception $e) {
            \Log::error('BMC approve email failed: ' . $e->getMessage());
        }

        Alert::success('Borrowing request has been approved')->persistent('Dimiss');
        return back();
    }

    public function releaseBmcItems(Request $request, $id) {
        $currentUser = auth()->user();

        // Only active BMC approvers can release items.
        $isApprover = \App\ApproverSetting::where(
            'user_id',
            $currentUser->id
        )
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->exists();

        if (!$isApprover) {
            Alert::error(
                'You do not have permission to release BMC items.'
            )->persistent('Dismiss');

            return back();
        }

        $validated = $request->validate([
            'release_notes' => 'nullable|string|max:2000',

            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',

            // The current BMC request does not store requested quantities.
            // Therefore, each selected item represents one physical item.
            'items.*.released_quantity' => 'required|integer|in:1',

            'items.*.serial_number' => 'required|string|max:100',
        ]);

        $result = DB::transaction(function () use (
            $id,
            $validated,
            $currentUser
        ) {
            // Read and hold this specific request while it is being processed.
            // This prevents a double-click from creating two releases.
            $borrowing = MarketingCollateralBorrowing::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$borrowing) {
                return [
                    'success' => false,
                    'message' => 'Borrowing request was not found.',
                ];
            }

            $released_at = now();

            // calculate the expected return date
            $expectedReturnAt = $released_at 
                ->copy()
                ->addDays((int) $borrowing->number_of_days_item_needed)
                ->toDateString();

            // Items can only be released after approval.
            if (
                $borrowing->status !==
                MarketingCollateralBorrowing::STATUS_APPROVED
            ) {
                return [
                    'success' => false,
                    'message' =>
                    'Only approved requests can have items released.',
                ];
            }

            /*
             * Get the items that were retained during approval.
             *
             * Example:
             * Booth, Negosyo Partner Roll Up Banner
             */
            $approvedItemNames = collect(
                explode(',', $borrowing->borrowed_items)
            )
                ->map(function ($item) {
                    return trim($item);
                })
                ->filter()
                ->unique()
                ->sort()
                ->values();

            // Get the item names submitted by the release modal.
            $submittedItemNames = collect($validated['items'])
                ->pluck('item_name')
                ->map(function ($item) {
                    return trim($item);
                })
                ->filter()
                ->unique()
                ->sort()
                ->values();

            /*
             * Ensure the submitted items exactly match the approved items.
             *
             * This prevents someone from:
             * - adding an item that was not approved;
             * - removing an approved item through a modified request;
             * - submitting the same item more than once.
             */
            $containsDuplicateItems =
                $submittedItemNames->count() !== count($validated['items']);

            $hasMissingItems = $approvedItemNames
                ->diff($submittedItemNames)
                ->isNotEmpty();

            $hasUnexpectedItems = $submittedItemNames
                ->diff($approvedItemNames)
                ->isNotEmpty();

            if (
                $containsDuplicateItems ||
                $hasMissingItems ||
                $hasUnexpectedItems
            ) {
                return [
                    'success' => false,
                    'message' =>
                    'The released items must match the approved items.',
                ];
            }

            // Generate the next accountability reference for this request.
            $releaseNumber = $borrowing->accountabilities()->count() + 1;

            $accountabilityReference =
                trim($borrowing->reference_no) . '-' . $releaseNumber;

            // Create the overall release record.
            $accountability = \App\BmcAccountability::create([
                'marketing_collateral_borrowing_id' => $borrowing->id,
                'accountability_ref' => $accountabilityReference,
                'released_by' => $currentUser->id,
                'released_at' => $released_at,
                'expected_return_at' => $expectedReturnAt,
                'release_notes' => $validated['release_notes'] ?? null,
                'status' => \App\BmcAccountability::STATUS_RELEASED,
            ]);

            // Create the individual released item records.
            foreach ($validated['items'] as $releasedItem) {
                $itemName = trim($releasedItem['item_name']);

                $accountability->items()->create([
                    'item_name' => $itemName,
                    'released_quantity' =>
                    (int) $releasedItem['released_quantity'],
                    'serial_number' =>
                    $releasedItem['serial_number'] ?? null,
                    'deposit_amount' => 0,
                    'returned_quantity' => 0,
                ]);
            }

            // The physical items have now left company custody.
            $borrowing->status =
                MarketingCollateralBorrowing::STATUS_RELEASED;

            $borrowing->save();

            return [
                'success' => true,
                'accountability_ref' =>
                $accountability->accountability_ref,
            ];
        });

        if (!$result['success']) {
            Alert::error($result['message'])->persistent('Dismiss');

            return back();
        }

        Alert::success(
            'Items released successfully. Accountability reference: ' .
            $result['accountability_ref']
        )->persistent('Dismiss');

        return back();
    }

    public function closeBmcAccountability(Request $request, $id) {
        $currentUser = auth()->user();

        $isApprover = \App\ApproverSetting::where(
            'user_id',
            $currentUser->id
        )
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->exists();

        if (!$isApprover) {
            Alert::error(
                'You do not have permission to close this accountability.'
            )->persistent('Dismiss');

            return back();
        }
        
        $validated = $request->validate([
            'closing_remarks' => 'nullable|string|max:2000',
        ]);

        $result = DB::transaction(function () use (
            $id,
            $validated,
            $currentUser
        ) {
        $accountability = \App\BmcAccountability::where('id', $id)
            ->lockForUpdate()
            ->first();

        if (!$accountability) {
            return [
                'success' => false,
                'message' => 'Accountability record was not found.',
            ];
        }

        if (
            $accountability->status !==
            \App\BmcAccountability::STATUS_RELEASED
        ) {
            return [
                'success' => false,
                'message' => 'Only released items can be closed.',
            ];
        }

        if (!$accountability->borrower_signature) {
            return [
                'success' => false,
                'message' =>
                    'The borrower must sign the accountability before it can be closed.',
            ];
        }

        // Record that every released item was returned.
        foreach ($accountability->items as $item) {
            $item->returned_quantity = $item->released_quantity;
            $item->save();
        }

        $accountability->status =
            \App\BmcAccountability::STATUS_CLOSED;

        $accountability->closed_by = $currentUser->id;
        $accountability->closed_at = now();
        $accountability->closing_remarks =
            $validated['closing_remarks'] ?? null;

        $accountability->save();

        // Close the main borrowing request as well.
        $borrowing = $accountability->borrowing;

        $borrowing->status =
            \App\MarketingCollateralBorrowing::STATUS_CLOSED;

        $borrowing->save();

        return ['success' => true];
    });

    if (!$result['success']) {
        Alert::error($result['message'])->persistent('Dismiss');

        return back();
    }

    Alert::success(
        'Borrowed item(s) were returned'
    )->persistent('Dismiss');

    return back();
    }

    public function printBmcAccountability($id) {
        $currentUser = auth()->user();

        $isApprover = \App\ApproverSetting::where('user_id', $currentUser->id)
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->exists();

        if (!$isApprover) {
            Alert::error('You do not have permission to print this accountability.')
                ->persistent('Dismiss');

            return back();
        }

        $accountability = \App\BmcAccountability::with([
            'borrowing',
            'items',
            'releasedBy',
        ])->findOrFail($id);

        return view('for-approval.print-accountability-bmc', [
            'accountability' => $accountability,
        ]);
    }

    public function updateBmcAccountabilityNote(Request $request, $id) {
        $isApprover = \App\ApproverSetting::where('user_id', auth()->id())
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->exists();

        if (!$isApprover) {
            return response()->json([
                'message' => 'You do not have permission to update issuance notes.',
            ], 403);
        }

        $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        $accountability = \App\BmcAccountability::findOrFail($id);
        $accountability->release_notes = $request->input('notes');
        $accountability->save();

        return response()->json([
            'success' => true,
            'message' => 'Issuance notes updated successfully.',
        ]);
    }

    public function declineBmcRequest(Request $request, $id) {
        $borrowing = MarketingCollateralBorrowing::find($id);


         if (!$borrowing) {
             Alert::error('Borrowing request not found.')->persistent('Dismiss');
             return back();
         }

        $current_user = auth()->user();

        $is_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->exists();

        if (!$is_approver) {
             Alert::error('You do not have persmission for this action.')->persistent('Dismiss');
             return back();
        }

        $borrowing->status = 'Declined';
        $borrowing->processed_at = now();
        $borrowing->mbd_remarks = $request->mbd_remarks;
        $borrowing->processed_by= $current_user->id;
        $borrowing->save();

        try {
            if ($borrowing->email) {
                Mail::to($borrowing->email)->send(new BmcMail($borrowing, 'declined'));
            }
        } catch (\Exception $e) {
            \Log::error('BMC declined email failed: ' . $e->getMessage());
        }

        Alert::success('Borrowing request declined')->persistent('Dismiss');
        return back();
    }

    // layout design request approval
    public function formLdrApproval(Request $request) {
        $approver = auth()->user()->id;

        $isApprover = \App\ApproverSetting::where('user_id', $approver)
            ->where('type_of_form', 'ldr')
            ->where('status', 'Active')
            ->exists();

        if (!$isApprover) {
            Alert::error('You do not have permission to access this resource.')->persistent('Dismiss');
            return back();
        }

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $search = $request->input('search');
        $from = $request->input('from');
        $to = $request->input('to');

        $query = LayoutDesign::where('status', $filter_status);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', '%' . $search . '%')
                  ->orWhere('requestor_first_name', 'like', '%' . $search . '%')
                  ->orWhere('requestor_last_name', 'like', '%' . $search . '%')
                  ->orWhere('requestor_email', 'like', '%' . $search . '%');
            });
        }

        if ($from) {
            $query->whereDate('date_needed', '>=', $from);
        }
        if ($to) {
            $query->whereDate('date_needed', '<=', $to);
        }

        $requests = $query->orderBy('submitted_at', 'DESC')->paginate(15);

        $pending = LayoutDesign::where('status', 'Pending')->count();
        $processing = LayoutDesign::where('status', 'Processing')->count();
        $closed = LayoutDesign::where('status', 'Closed')->count();
        $declined = LayoutDesign::where('status', 'Declined')->count();
        $cancelled = LayoutDesign::where('status', 'Cancelled')->count();

        return view('for-approval.ldr-approval',
            array(
                'header'        => 'for-approval',
                'requests'      => $requests,
                'filter_status' => $filter_status,
                'search'        => $search,
                'from'          => $from,
                'to'            => $to,
                'pending'       => $pending,
                'processing'    => $processing,
                'closed'        => $closed,
                'declined'      => $declined,
                'cancelled'      => $cancelled,
                'approver_id'   => $approver
            )
        );
    }

    // close -> approve it's just the naming convention
    public function closeLdrRequest(Request $request, $id) {
        $ldr = LayoutDesign::find($id);

         if (!$ldr) {
             Alert::error('Layout design request not found.')->persistent('Dismiss');
             return back();
         }

        $current_user = auth()->user();

        $is_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'ldr')
            ->where('status', 'Active')
            ->exists();

        if (!$is_approver) {
             Alert::error('You do not have privilege for this action.')->persistent('Dismiss');
             return back();
        }

        if ($ldr->status !== 'Processing') {
            Alert::error('Request cannot be closed.')->persistent('Dismiss');
            return back();
        }

        $validated = $request->validate([
            'issuance_date' => ['required', 'date', 'after_or_equal:today'],
            'closing_remarks' => ['required', 'string'],
            'output_attachment' => [
                'required',
                'mimes:jpg,jpeg,png,pdf,doc,docx',
                'max:20480',
            ],
        ], [
            'issuance_date.required' => 'Issuance date is required when closing a request.',
            'issuance_date.after_or_equal' => 'Issuance date cannot be earlier than today.',
            'closing_remarks.required' => 'Remarks are required when closing a request.',
            'output_attachment.required' => 'Output attachment is required.',
            'output_attachment.mimes' => 'The output must be a JPG, JPEG, PNG, PDF, Word',
            'output_attachment.max' => 'The output attachment must not exceed 20 MB.',
        ]);

        $file = $request->file('output_attachment');
        $fileName = time() . '_' . $ldr->id . '_' . preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $file->getClientOriginalName()
        );

        $destDir = public_path('ldr_outputs');
        if (!file_exists($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $file->move($destDir, $fileName);

        $ldr->status = 'Closed';
        $ldr->issuance_date = $validated['issuance_date'];
        $ldr->closing_remarks = $validated['closing_remarks'];
        $ldr->output_attachment = '/ldr_outputs/' . $fileName;
        $ldr->save();

        $emailSent = true;
        try {
            if ($ldr->requestor_email) {
                Mail::to($ldr->requestor_email)->send(new LayoutDesignMail($ldr, 'closed'));
            }
        } catch (\Exception $e) {
            $emailSent = false;
            \Log::error('LDR closed email failed: ' . $e->getMessage());
        }

        if (!$emailSent) {
            Alert::warning('Request was closed, but the email could not be sent.')->persistent('Dismiss');
            return back();
        }

        Alert::success('Layout design request has been closed and sent to the requestor.')->persistent('Dismiss');
        return back();
    }

    public function processLdrRequest(Request $request, $id) {
        $ldr = LayoutDesign::find($id);

        if (!$ldr) {
            Alert::error('Layout design request not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();

        $is_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'ldr')
            ->where('status', 'Active')
            ->exists();

        if (!$is_approver) {
            Alert::error('You do not have privilege for this action.')->persistent('Dismiss');
            return back();
        }

        if ($ldr->status !== 'Pending') {
            Alert::error('Only pending requests can be processed')->persistent('Dismiss');
            return back();
        }

        $ldr->status = 'Processing';
        if ($request->filled('remarks')) {
            $ldr->remarks = $request->input('remarks');
        }
        $ldr->save();

        try {
            if ($ldr->requestor_email) {
                Mail::to($ldr->requestor_email)->send(new LayoutDesignMail($ldr, 'processing'));
            }
        } catch (\Exception $e) {
            \Log::error('LDR processing email failed: ' . $e->getMessage());
        }

        Alert::success('Request has been processed.')->persistent('Dismiss');
        return back();
    }

    public function declineLdrRequest(Request $request, $id) {
        $ldr = LayoutDesign::find($id);

         if (!$ldr) {
             Alert::error('Layout design request not found.')->persistent('Dismiss');
             return back();
         }

        $current_user = auth()->user();

        $is_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'ldr')
            ->where('status', 'Active')
            ->exists();

        if (!$is_approver) {
             Alert::error('You do not have persmission for this action')->persistent('Dismiss');
             return back();
        }

        if ($ldr->status !== 'Pending') {
            Alert::error('Only pending requests can be declined')->persistent('Dismiss');

            return back();
        }

        $validated = $request->validate([
            'remarks' => ['required', 'string'],
        ], [
            'remarks.required' => 'Remarks are required when declining a request.',
        ]);

        $oldPegSamplePath = $ldr->peg_sample_path;
        $currentRequestId = $ldr->id;

        $ldr->status = 'Declined';
        $ldr->remarks = $validated['remarks'];
        $ldr->peg_sample_path = null;
        $ldr->save();

        if ($oldPegSamplePath) {
            $usedByAnotherRequest = LayoutDesign::where(
                'peg_sample_path',
                $oldPegSamplePath
            )
                ->where('id', '!=', $currentRequestId)
                ->exists();

            if (!$usedByAnotherRequest) {
                $oldFile = public_path(
                    'peg_samples/' . basename($oldPegSamplePath)
                );

                if (is_file($oldFile)) {
                    $deleted = \Illuminate\Support\Facades\File::delete($oldFile);

                    if (!$deleted) {
                        \Log::warning(
                            "Failed to delete peg sample for LDR ID {$currentRequestId}: {$oldFile}"
                        );
                    }
                }
            }
        }

        try {
            /* if ($ldr->requestor_email) { */
            /*     Mail::to($ldr->requestor_email)->send(new LayoutDesignMail($ldr, 'declined')); */
            /* } */
        } catch (\Exception $e) {
            \Log::error('LDR decline email failed: ' . $e->getMessage());
        }

        Alert::success('Layout design request declined.')->persistent('Dismiss');
        return back();
    }

    public function viewLdrRequest($id) {
        $current_user = auth()->user();

        $is_approver = \App\ApproverSetting::where('user_id', $current_user->id)
            ->where('type_of_form', 'ldr')
            ->where('status', 'Active')
            ->exists();

        if (!$is_approver) {
            Alert::error('You do not have permission to access this resource.')->persistent('Dismiss');
            return back();
        }

        $request = LayoutDesign::find($id);

        if (!$request) {
            Alert::error('Layout design request not found.')->persistent('Dismiss');
            return back();
        }

        return view('forms.ldr.view-ldr', compact('request') + ['header' => 'for-approval']);
    }

    // publication request approval
    public function viewPublicationRequest(
        $id,
        PublicationRequestWorkflowService $workflow
    ) {
        $isEmailPublisher = strcasecmp(
            auth()->user()->email,
            (string) config('publication.email_publisher')
        ) === 0;

        if (!$workflow->isWorkflowApprover(auth()->user()) && !$isEmailPublisher) {
            Alert::error('You do not have permission to access this resource.')->persistent('Dismiss');
            return back();
        }

        $pr = PublicationRequest::findOrFail($id);

        return view('for-approval.view-publication', array(
            'header' => 'for-approval',
            'pr' => $pr,
            'canAct' => $workflow->canAct($pr, auth()->user()),
        ));
    }

    public function formPublicationApproval(
        Request $request,
        PublicationRequestWorkflowService $workflow
    ) {
        $isEmailPublisher = strcasecmp(
            auth()->user()->email,
            (string) config('publication.email_publisher')
        ) === 0;

        if (!$workflow->isWorkflowApprover(auth()->user()) && !$isEmailPublisher) {
            Alert::error('You do not have privilege to access this resource.')->persistent('Dismiss');
            return back();
        }

        $approver = auth()->user()->id;

        $isPublicationApprover = strcasecmp(
            auth()->user()->email,
            (string) config('publication.final_approver')
        ) === 0;

        $actionableStatuses = $workflow->pendingStatusesFor(
            auth()->user()
        );

        $approvedPublishedFilter = 'Approved / Published';

        $filter_status = $request->filled('status')
            ? $request->status
            : ($isEmailPublisher
                ? $approvedPublishedFilter
                : ($actionableStatuses[0] ?? PublicationRequest::STATUS_FOR_REVIEW));

        if ($filter_status === $approvedPublishedFilter) {
            $query = PublicationRequest::whereIn('status', [
                PublicationRequest::STATUS_APPROVED,
                PublicationRequest::STATUS_PUBLISHED,
            ]);
        } else {
            $query = PublicationRequest::where('status', $filter_status);
        }

        if (
            $isPublicationApprover &&
            in_array($filter_status, [
                PublicationRequest::STATUS_APPROVED,
                PublicationRequest::STATUS_PUBLISHED,
                PublicationRequest::STATUS_DECLINED,
                $approvedPublishedFilter,
            ], true)
        ) {
            $query->where('approved_by', $approver);
        }

        if ($request->filled('from')) {
            $query->whereDate('submitted_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('submitted_at', '<=', $request->to);
        }

        if ($filter_status === $approvedPublishedFilter) {
            $query->orderByRaw(
                'CASE
            WHEN status = ? THEN 0
            WHEN status = ? THEN 1
            ELSE 2
        END ASC',
                [
                    PublicationRequest::STATUS_APPROVED,
                    PublicationRequest::STATUS_PUBLISHED,
                ]
            );
        }

        $requests = $query->orderBy('submitted_at', 'DESC')->get();

        foreach ($requests as $publicationRequest) {
            $publicationRequest->can_current_user_act = $workflow->canAct(
                $publicationRequest,
                auth()->user()
            );
        }

        // cards
        $for_review = PublicationRequest::where(
            'status',
            PublicationRequest::STATUS_FOR_REVIEW
        )->count();

        $for_approval = PublicationRequest::where(
            'status',
            PublicationRequest::STATUS_FOR_APPROVAL
        )->count();

        $for_publication = PublicationRequest::where(
            'status',
            PublicationRequest::STATUS_FOR_PUBLICATION
        )->count();

        $approvedAndPublishedQuery = PublicationRequest::whereIn('status', [
            PublicationRequest::STATUS_APPROVED,
            PublicationRequest::STATUS_PUBLISHED,
        ]);

        $declinedQuery = PublicationRequest::where(
            'status',
            PublicationRequest::STATUS_DECLINED
        );

        if ($isPublicationApprover) {
            $approvedAndPublishedQuery->where('approved_by', $approver);
            $declinedQuery->where('approved_by', $approver);
        }

        $approvedAndPublished = $approvedAndPublishedQuery->count();
        $declined = $declinedQuery->count();

        $recipientEmails = \App\User::whereHas('employee', function ($query) {
            $query->where('status', 'Active');
        })
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->distinct()
            ->orderBy('email')
            ->pluck('email');

        return view('for-approval.publication-approval',
            array(
                'header'        => 'for-approval',
                'requests'      => $requests,
                'filter_status' => $filter_status,
                'from'          => $request->from,
                'to'            => $request->to,
                'for_review'      => $for_review,
                'for_approval'    => $for_approval,
                'for_publication' => $for_publication,
                'approved_and_published' => $approvedAndPublished,
                'declined'        => $declined,
                'approver_id'     => $approver,
                'recipientEmails' => $recipientEmails,
                'is_email_publisher' => $isEmailPublisher
            )
        );
    }

    public function approvePublicationRequest(
        Request $request,
        $id,
        PublicationRequestWorkflowService $workflow
    ) {
        $pr = PublicationRequest::find($id);

        if (!$pr) {
            Alert::error('Publication request not found.')->persistent('Dismiss');
            return back();
        }

        $isForReview =
            $pr->status === PublicationRequest::STATUS_FOR_REVIEW;

        $request->validate([
            'approval_remarks' => $isForReview
                ? 'required|string|max:5000'
                : 'nullable|string|max:5000',
            'memo' => $isForReview
                ? 'required|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,mp3,mp4|max:10240'
                : 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,mp3,mp4|max:10240',
        ]);

        $user = auth()->user();
        $remarks = $request->approval_remarks;

        try {
            if ($pr->status === PublicationRequest::STATUS_FOR_REVIEW) {
                $pr = $workflow->initialReview(
                    $pr,
                    $user,
                    'Approved',
                    $remarks
                );
            } elseif (
                $pr->status === PublicationRequest::STATUS_FOR_APPROVAL
            ) {
                $pr = $workflow->primaryApprove(
                    $pr,
                    $user,
                    'Approved',
                    $remarks
                );
            } elseif (
                $pr->status === PublicationRequest::STATUS_FOR_PUBLICATION
            ) {
                $pr = $workflow->finalApprove(
                    $pr,
                    $user,
                    'Approved'
                );
            } else {
                Alert::error('This request can no longer be approved.')
                    ->persistent('Dismiss');

                return back();
            }
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Alert::error($e->getMessage())->persistent('Dismiss');
            return back();
        }

        if ($isForReview && $request->hasFile('memo')) {
            $file = $request->file('memo');
            $fileName = time() . '_' . $pr->id . '_' . preg_replace(
                '/[^A-Za-z0-9._-]/',
                '_',
                $file->getClientOriginalName()
            );

            $destination = public_path('publication_memos');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $fileName);
            $pr->memo = '/publication_memos/' . $fileName;
            $pr->save();
        }

        // sweet alert messages content
        if ($pr->status === PublicationRequest::STATUS_FOR_APPROVAL) {
            $message = 'Request reviewed and submitted for approval';
        } elseif (
            $pr->status === PublicationRequest::STATUS_FOR_PUBLICATION
        ) {
            $message = 'Request approved and submitted for publication';
        } elseif (!$pr->requiresElectronicApproval()) {
            $message = 'Approved for Publication';
        } else {
            $message = 'Approved for Electronic Publication';
        }

        Alert::success($message)->persistent('Dismiss');
        return back();
    }
    
    // ethan's part for publication
    public function sendPublication(Request $request, $id) {
        $user = auth()->user();

        // only the configured email publisher can perform this action.
        $publisherEmail = strtolower(trim(
            (string) config('publication.email_publisher')
        ));

        $userEmail = strtolower(trim($user->email));

        if ($userEmail !== $publisherEmail) {
            Alert::error(
                'You are not authorized to send this publication.'
            )->persistent('Dismiss');

            return back();
        }

        $pr = PublicationRequest::find($id);

        if (!$pr) {
            Alert::error(
                'Publication request not found'
            )->persistent('Dismiss');

            return back();
        }

        if (
            $pr->status !==
            PublicationRequest::STATUS_APPROVED
        ) {
            Alert::error(
                'This request is not ready for distribution'
            )->persistent('Dismiss');

            return back();
        }

        // $validated = $request->validate([
        //     'recipients' => 'required|array|min:1',
        //     'recipients.*' => 'required|email|distinct',

        //     'copy_type' => 'nullable|in:cc,bcc',
        //     'copy_recipients' => 'nullable|required_with:copy_type|array|min:1',
        //     'copy_recipients.*' => 'required|email|distinct',
        // ]);

        // $recipients = array_values(array_unique(
        //     array_map('trim', $validated['recipients'])
        // ));

        // $copyType = $validated['copy_type'] ?? null;

        // $copyRecipients = array_values(array_unique(
        //     array_map(
        //         'trim',
        //         $validated['copy_recipients'] ?? []
        //     )
        // ));

        try {
            // $mail = new \App\Mail\PublicationMail(
            //     $pr,
            //     'publication_distributed',
            //     [
            //         'publisher_name' => $user->name ?: $user->email,
            //     ]
            // );

            // $pendingMail = Mail::to($recipients);

            // if ($copyType === 'cc') {
            //     $pendingMail->cc($copyRecipients);
            // } elseif ($copyType === 'bcc') {
            //     $pendingMail->bcc($copyRecipients);
            // }

            // $pendingMail->send($mail);

            $publisherName = $user->name ?: $user->email;

            if ($user->employee) {
                $publisherName = trim(
                    $user->employee->first_name . ' ' .
                    $user->employee->last_name
                );
            }

            $history = $pr->approvalHistory();

            $history[] = [
                'stage' => 'Publishing',
                'status' => 'Published',
                'publisher_id' => $user->id,
                'publisher_name' => $publisherName,
                'publisher_email' => $user->email,
                'remarks' => null,
                'published_at' => now()->format('Y-m-d H:i:s'),
            ];

            $pr->setApprovalHistory($history);
            $pr->status = PublicationRequest::STATUS_PUBLISHED;
            $pr->save();
        } catch (\Throwable $exception) {
            Log::error('Publication status update failed.', [
                'publication_request_id' => $pr->id,
                'error' => $exception->getMessage(),
            ]);
        
        // catch (\Exception $e) {
        //     Log::error('Publication distribution failed.', [
        //         'publication_request_id' => $pr->id,
        //         'publisher_id' => $user->id,
        //         'recipients' => $recipients,
        //         'message' => $e->getMessage(),
        //     ]);

            Alert::error(
                // 'The publication email could not be sent. Please try again.'
                'Publishing Failed. Please try again'
            )->persistent('Dismiss');

            // return back()->withInput();
            return back();
        }

        Alert::success('Request has been published')->persistent('Dismiss');
        return back();
    }

    // for declining request for publishing
    public function declinePublicationPublishing(Request $request, $id) {
        $user = auth()->user();

        $publisherEmail = strtolower(trim(
            (string) config('publication.email_publisher')
        ));

        $userEmail = strtolower(trim($user->email));

        if ($userEmail !== $publisherEmail) {
            Alert::error(
                'You are not authorized to decline this publication.'
            )->persistent('Dismiss');

            return back();
        }

        $validated = $request->validate([
            'publishing_remarks' => 'required|string|max:5000',
        ]);

        $pr = DB::transaction(function () use (
            $id,
            $user,
            $validated
        ) {
            $pr = PublicationRequest::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $pr->status !== PublicationRequest::STATUS_APPROVED ||
                !$pr->requiresElectronicApproval()
            ) {
                throw ValidationException::withMessages([
                    'publishing_remarks' =>
                    'This request is not available for publishing.',
                ]);
            }

            $publisherName = $user->name ?: $user->email;

            if ($user->employee) {
                $publisherName = trim(
                    $user->employee->first_name . ' ' .
                    $user->employee->last_name
                );
            }

            $history = $pr->approvalHistory();

            $history[] = [
                'stage' => 'Publishing',
                'status' => 'Declined',
                'publisher_id' => $user->id,
                'publisher_name' => $publisherName,
                'publisher_email' => $user->email,
                'remarks' => $validated['publishing_remarks'],
                'approve_at' => now()->format('Y-m-d H:i:s'),
            ];

            $pr->setApprovalHistory($history);
            $pr->status = PublicationRequest::STATUS_DECLINED;
            $pr->approved_by = $user->id;
            $pr->approved_date = now();
            $pr->save();

            return $pr;
        });

        Alert::success(
            'Publication request declined.'
        )->persistent('Dismiss');

        return back();
    }

    public function declinePublicationRequest(
        Request $request,
        $id,
        PublicationRequestWorkflowService $workflow
    ) {
        $pr = PublicationRequest::find($id);

        if (!$pr) {
            Alert::error('Publication request not found.')
                ->persistent('Dismiss');

            return back();
        }

        $requiresRemarks = in_array($pr->status, [
            PublicationRequest::STATUS_FOR_REVIEW,
            PublicationRequest::STATUS_FOR_APPROVAL,
        ], true);

        $request->validate([
            'approval_remarks' => $requiresRemarks
                ? 'required|string|max:5000'
                : 'nullable|string|max:5000',
        ]);

        $user = auth()->user();
        $remarks = $request->approval_remarks;

        try {
            if ($pr->status === PublicationRequest::STATUS_FOR_REVIEW) {
                $pr = $workflow->initialReview(
                    $pr,
                    $user,
                    'Declined',
                    $remarks
                );
            } elseif (
                $pr->status === PublicationRequest::STATUS_FOR_APPROVAL
            ) {
                $pr = $workflow->primaryApprove(
                    $pr,
                    $user,
                    'Declined',
                    $remarks
                );
            } elseif (
                $pr->status === PublicationRequest::STATUS_FOR_PUBLICATION
            ) {
                $pr = $workflow->finalApprove(
                    $pr,
                    $user,
                    'Declined'
                );
            } else {
                Alert::error('This request can no longer be declined.')
                    ->persistent('Dismiss');

                return back();
            }
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Alert::error($e->getMessage())->persistent('Dismiss');

            return back();
        }

        Alert::success('Publication request declined.')
            ->persistent('Dismiss');

        return back();
    }
}
