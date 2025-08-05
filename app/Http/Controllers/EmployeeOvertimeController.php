<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeeOvertime;
use App\Attendance;
use App\ScheduleData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
class EmployeeOvertimeController extends Controller
{
    public function overtime(Request $request)
        { 
            $today = date('Y-m-d');
            $from = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
            $to = isset($request->to) ? $request->to : date('Y-m-d');
            $status = isset($request->status) ? $request->status : 'Pending';

            // Remove the permission check - allow everyone to access
            $get_approvers = new EmployeeApproverController;

            $overtimes = EmployeeOvertime::where('user_id',auth()->user()->id)
                                            ->where('status',$status)
                                            ->whereDate('start_time', '>=', $from)
                                            ->whereDate('end_time', '<=', $to)
                                            ->orderBy('created_at','DESC')
                                            ->get();
            
            $overtimes_all = EmployeeOvertime::where('user_id',auth()->user()->id)->get();
            
            $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
            
            return view('forms.overtime.overtime',
                array(
                    'header' => 'forms',
                    'all_approvers' => $all_approvers,
                    'overtimes' => $overtimes,
                    'overtimes_all' => $overtimes_all,
                    'from' => $from,
                    'to' => $to,
                    'status' => $status,
                )
            );
        }


    public function new(Request $request)
    {
        $validate = EmployeeOvertime::where('user_id',auth()->user()->id)
                                        ->where('ot_date',date('Y-m-d',strtotime($request->ot_date)))
                                        ->whereIn('status',['Pending','Approved'])
                                        ->first();
        // if(empty($validate)){
            $new_overtime = new EmployeeOvertime;
            $new_overtime->user_id = Auth::user()->id;
            $emp = Employee::where('user_id',auth()->user()->id)->first();
            $new_overtime->schedule_id = $emp->schedule_id;
            $new_overtime->ot_date = $request->ot_date;
            $new_overtime->start_time =  date('Y-m-d H:i:s',strtotime($request->start_time));
            $new_overtime->end_time = date('Y-m-d H:i:s',strtotime($request->end_time));     
            $new_overtime->break_hrs = $request->break_hrs;  
            $new_overtime->time_compensation_type = $request->time_compensation_type;   

            $new_overtime->remarks = $request->remarks;

            if($request->file('attachment')){
                $logo = $request->file('attachment');
                $original_name = $logo->getClientOriginalName();
                $name = time() . '_' . $logo->getClientOriginalName();
                $logo->move(public_path() . '/images/', $name);
                $file_name = '/images/' . $name;
                $new_overtime->attachment = $file_name;
            }

            $new_overtime->status = 'Pending';
            $new_overtime->level = 0;
            $new_overtime->created_by = Auth::user()->id;
            $new_overtime->save();

            Alert::success('Successfully Stored')->persistent('Dismiss');
            return back();
        // }else{
        //     Alert::warning('Overtime Application is already exist.')->persistent('Dismiss');
        //     return back();
        // }
    }

    public function newOffSet(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'employee_email' => 'required|email|max:255',
            'department' => 'required|string',
            'ot_date' => 'required|date',
            'total_hours' => 'required|numeric|min:1',
            'time_compensation_type' => 'required|string|max:255',
            'proof_otar' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
            'detailed_description' => 'required|string',
        ]);

        $validate = EmployeeOvertime::where('user_id',auth()->user()->id)
                                        ->where('ot_date',date('Y-m-d',strtotime($request->ot_date)))
                                        ->whereIn('status',['Pending','Approved'])
                                        ->first();
        
        if(empty($validate)){
            $new_overtime = new EmployeeOvertime;
            $new_overtime->user_id = Auth::user()->id;

            $emp = Employee::where('user_id', auth()->user()->id)->first();
            $schedule_end_time = '17:00:00'; // Default fallback if the schedule_id is null

            if($emp) {
                $new_overtime->schedule_id = $emp->schedule_id;

                if ($emp->schedule_id) {
                    $schedule = DB::table('schedule_datas')
                        ->where('schedule_id', $emp->schedule_id)
                        ->first();
                    
                    if ($schedule && $schedule->time_out_to) {
                        $schedule_end_time = strlen(trim($schedule->time_out_to)) == 5 
                            ? trim($schedule->time_out_to) . ':00' 
                            : trim($schedule->time_out_to);
                    }
                }
            }

            $new_overtime->first_name = $request->first_name;
            $new_overtime->last_name = $request->last_name;
            $new_overtime->designation = $request->designation;
            $new_overtime->employee_email = $request->employee_email;
            $new_overtime->department = $request->department;
            $new_overtime->ot_authorization_ref = $request->ot_authorization_ref;
            $new_overtime->ot_date = $request->ot_date;
            $new_overtime->total_hours = $request->total_hours;
            $new_overtime->time_compensation_type = $request->time_compensation_type;
            
            $new_overtime->remarks = $request->detailed_description;
             
            $ot_date = date('Y-m-d', strtotime($request->ot_date));
            $start_time = $ot_date . ' ' . $schedule_end_time;
            $end_time = date('Y-m-d H:i:s', strtotime($start_time . ' + ' . $request->total_hours . ' hours'));
            
            $new_overtime->start_time = $start_time;
            $new_overtime->end_time = $end_time;
            
            if($request->hasFile('proof_otar')){
                $file = $request->file('proof_otar');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $uploadPath = public_path() . '/images/';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $file->move($uploadPath, $fileName);
                
                $new_overtime->attachment = '/images/' . $fileName;
            }

            $new_overtime->status = 'Pending';
            $new_overtime->level = 0;
            $new_overtime->created_by = Auth::user()->id;
            $new_overtime->save();

            Alert::success('Overtime application submitted successfully')->persistent('Dismiss');
            return back();
        } else {
            Alert::warning('Overtime Application already exists for this date.')->persistent('Dismiss');
            return back();
        }
    }

    public function edit_overtime(Request $request, $id)
    {
        $new_overtime = EmployeeOvertime::findOrFail($id);
        $new_overtime->user_id = Auth::user()->id;
        $new_overtime->ot_date = $request->ot_date;
        $new_overtime->start_time =  date('Y-m-d H:i:s',strtotime($request->start_time));
        $new_overtime->end_time = date('Y-m-d H:i:s',strtotime($request->end_time)); 
        $logo = $request->file('attachment');
        if(isset($logo)){
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $new_overtime->attachment = $file_name;
        }
        $new_overtime->status = 'Pending';
        $new_overtime->level = 0;
        $new_overtime->created_by = Auth::user()->id;
        $new_overtime->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }    

    public function disable_overtime($id)
    {
        EmployeeOvertime::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('Overtime has been cancelled.')->persistent('Dismiss');
        return back();
    }
    
    public function checkValidOvertime(Request $request){

        $date = date('Y-m-d',strtotime($request->date));
        $name_day = date('l',strtotime($request->date));
        $emp = Employee::where('employee_number',$request->employee_number)->first();
        $attendance = Attendance::where('employee_code',$request->employee_number)
                                    ->where(function($q) use($date){
                                        $q->whereDate('time_in', $date)
                                        ->orWhereDate('time_out', $date);
                                    })
                                    ->first();
        $employee_schedule = ScheduleData::where('schedule_id',$emp->schedule_id)    
                                    ->where('name',$name_day)
                                    ->first();

        $if_has_ob = employeeHasOBDetails($emp->approved_obs,date('Y-m-d',strtotime($request->date)));
        $if_has_wfh = employeeHasWFHDetails($emp->approved_wfhs,date('Y-m-d',strtotime($request->date)));
        $if_has_dtr = employeeHasDTRDetails($emp->approved_dtrs,date('Y-m-d',strtotime($request->date)));

        $allowed_overtime_hrs = 0;
        $start_time = '';
        $end_time = '';
        
        $working_hours = 0;
        
        $time_in = $attendance ? $attendance->time_in : "";
        $time_out = $attendance ? $attendance->time_out : "";
        $type = $attendance ? 'Attendance' : "";

        if($if_has_wfh){
            if($if_has_wfh->date_from && $if_has_wfh->date_to){
                $wfh_start = new DateTime($if_has_wfh->date_from); 
                $wfh_diff = $wfh_start->diff(new DateTime($if_has_wfh->date_to));
                $working_hours = round($wfh_diff->s / 3600 + $wfh_diff->i / 60 + $wfh_diff->h + $wfh_diff->days * 24, 2);

                $time_in = $if_has_wfh->applied_date . ' ' . $if_has_wfh->date_from;
                $time_out = $if_has_wfh->applied_date . ' ' . $if_has_wfh->date_to;
                $type = 'WFH';
            }  
        }

        if($if_has_ob){
            if($if_has_ob->date_from && $if_has_ob->date_to){
                $ob_start = new DateTime($if_has_ob->date_from); 
                $ob_diff = $ob_start->diff(new DateTime($if_has_ob->date_to));
                $working_hours = round($ob_diff->s / 3600 + $ob_diff->i / 60 + $ob_diff->h + $ob_diff->days * 24, 2);

                $time_in = $if_has_ob->applied_date . ' ' . $if_has_ob->date_from;
                $time_out = $if_has_ob->applied_date . ' ' . $if_has_ob->date_to;
                $type = 'OB';
            }  
        }

        if($if_has_dtr){
            if($if_has_dtr->correction == 'Time-in'){
                $time_in = $if_has_dtr->time_in;
            }
            elseif($if_has_dtr->correction == 'Time-out'){
                $time_out = $if_has_dtr->time_out;
            }
            elseif($if_has_dtr->correction == 'Both'){
                $time_in = $if_has_dtr->time_in;
                $time_out = $if_has_dtr->time_out;
            }

            if($time_in && $time_out){
                $dtr_start = new DateTime($time_in); 
                $dtr_diff = $dtr_start->diff(new DateTime($time_out));
                $working_hours = round($dtr_diff->s / 3600 + $dtr_diff->i / 60 + $dtr_diff->h + $dtr_diff->days * 24, 2);
            }

            $type = 'DTR';
            
        }else{
            if($time_in && $time_out){
                $att_start = new DateTime($time_in); 
                $att_diff = $att_start->diff(new DateTime($time_out));
                $working_hours = round($att_diff->s / 3600 + $att_diff->i / 60 + $att_diff->h + $att_diff->days * 24, 2);
            }
        }
        
        if($employee_schedule){
            if($working_hours > $employee_schedule->working_hours){
                $allowed_overtime_hrs = (double) number_format($working_hours - $employee_schedule->working_hours,2);

                if($time_out){
                    $date = new DateTime($time_out);
                    $decimalHours = $allowed_overtime_hrs;

                    // Convert decimal hours to minutes
                    $minutes = floor($decimalHours * 60 * 60);

                    // Create a negative DateInterval object with the specified minutes
                    $interval = new DateInterval('PT' . $minutes . 'S');

                    // Subtract the interval from the date
                    $date->sub($interval);

                    // Format the resulting date
                    $start_time = $date->format('Y-m-d H:i:s');
                    $end_time = date('Y-m-d H:i:s',strtotime($time_out));                    
                }
                
            }
        }else{
            if($working_hours > 0){
                $allowed_overtime_hrs = (double) number_format($working_hours,2);
                $start_time = date('Y-m-d H:i:s',strtotime($time_in));
                $end_time = date('Y-m-d H:i:s',strtotime($time_out));
            } 
        }

        return [
            'allowed_overtime_hrs' => $allowed_overtime_hrs,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'type' => $type
        ];
      
      
    }

    public function uploadOvertimeAttachments(Request $request, $id) {
      $request->validate([
        'file' => 'mimes:jpg,png,pdf,doc,docx|max:2048'
      ]);

      $file = $request->file('file');
      $fileName = time().'_'.$file->getClientOriginalName();
      $file->move(public_path().'/storage/overtime_attachments/', $fileName);

      $overtime = EmployeeOvertime::findOrFail($id);
      $overtime->file_path = '/storage/overtime_attachments/'.$fileName;
      $overtime->file_name = $fileName;
      $overtime->save();

      Alert::success('Successfully Uploaded.')->persistent('Dismiss');
      return back();
    }
}