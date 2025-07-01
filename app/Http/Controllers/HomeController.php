<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use App\Attendance;
use App\Handbook;
use App\Employee;
use App\Announcement;
use App\Classification;
use App\ScheduleData;
use App\Holiday;
use App\Document;
use App\EmployeeLeave;
use App\EmployeeOvertime;
use App\EmployeeWfh;
use App\EmployeeOb;
use App\EmployeeDtr;
use App\EmployeeLeaveCredit;
use App\Leave;
use Carbon\Carbon;
use App\LeavePlan;
use stdClass;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $documents = Document::get();
        $schedules = [];
        $attendance_controller = new AttendanceController;
        $current_day = date('d');
        $employee_birthday_celebrants = Employee::whereMonth('birth_date', date('m'))
        ->where(function($query) {
            $query->where('status', 'Active')
                  ->orWhere('status', 'HBU');
        })
        ->orderByRaw("DAY(birth_date) >= ? DESC, DAY(birth_date)", [$current_day])
        ->get();
        
        $employees_new_hire = Employee::where('original_date_hired',">=",date("Y-m-d", strtotime("-1 months")))->orderBy('original_date_hired','desc')->get();
        $sevendays = date('Y-m-d',strtotime("-7 days"));
        if(auth()->user()->employee){
            if(auth()->user()->employee->employee_number){
                $attendance_now = $attendance_controller->get_attendance_now(auth()->user()->employee->employee_number);
                $attendances = $attendance_controller->get_attendances($sevendays,date('Y-m-d',strtotime("-1 day")),auth()->user()->employee->employee_number);
            }else{
                $attendance_now = null;
                $attendances = null;
            }

            $schedules = ScheduleData::where('schedule_id',auth()->user()->employee->schedule_id)->get();
        }else{
            $attendance_now = null;
            $attendances = null;
        }
        // dd($attendances->unique('time_in','Y-m-d'));
        $date_ranges = $attendance_controller->dateRange($sevendays,date('Y-m-d',strtotime("-1 day")));
        $handbook = Handbook::orderBy('id','desc')->first();
        $employees_under = auth()->user()->subbordinates;
        // dd(auth()->user()->employee);
        $attendance_employees = $attendance_controller->get_attendances_employees(date('Y-m-d'),date('Y-m-d'),$employees_under->pluck('employee_number')->toArray());
        $attendance_employees->load('employee.approved_leaves_with_pay');
        // dd($attendance_employees);
        $announcements = Announcement::with('user')->where('expired',null)
        ->orWhere('expired',">=",date('Y-m-d'))->get();
        

        $holidays = Holiday::where('status','Permanent')
        ->whereMonth('holiday_date',date('m'))
        ->orWhere(function ($query)
        {
            $query->where('status',null)->whereYear('holiday_date', '=', date('Y'))->whereMonth('holiday_date',date('m'));
        })
        ->orderBy('holiday_date','asc')->get();

        $employee_anniversaries = Employee::with('department', 'company') ->where(function($query) {
            $query->where('status', 'Active');
        })
          ->whereYear('original_date_hired','!=',date('Y'))
          ->whereMonth('original_date_hired', date('m'))
          ->get();

        $probationary_employee = Employee::with('department', 'company', 'user_info', 'classification_info')
            ->where('classification', "1")
            ->where(function($query) {
                $query->where('status', 'Active')
                      ->orWhere('status', 'HBU');
            })
            ->orderBy('original_date_hired')
            ->get();

        $classifications = Classification::get();
        $leaveTypes = Leave::all();

        $usedLeaves = EmployeeLeave::where('user_id', auth()->user()->id)
            ->where('status', 'Approved')
            ->whereDate('date_to', '<=', Carbon::today())
            ->get();

        $totalUsedLeaveDays = $usedLeaves->reduce(function ($carry, $leave) {
            $from = Carbon::parse($leave->date_from);
            $to = Carbon::parse($leave->date_to);

            if ($leave->halfday) {
                return $carry + 0.5;
            }

            return $carry + $from->diffInDays($to) + 1;
        }, 0);


        $lateCount = null;

        if (auth()->user()->employee && auth()->user()->employee->employee_number) {
            $employeeNumber = auth()->user()->employee->employee_number;

            $lateRecords = Attendance::where('employee_code', $employeeNumber)
            ->whereTime('time_in', '>', '09:01:00')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('time_in', 'asc')
            ->get(['time_in', 'created_at'])
            ->map(function ($record) {
                if (is_null($record->created_at) || is_null($record->time_in)) {
                    return null;
                }

                try {
                    $date = \Carbon\Carbon::parse($record->created_at)->format('Y-m-d');
                    $time = \Carbon\Carbon::parse($record->time_in)->format('H:i:s');
                    $datetime = \Carbon\Carbon::parse("$date $time");

                    $lateMinutes = $datetime->greaterThan(\Carbon\Carbon::parse("$date 09:00:00"))
                        ? $datetime->diffInMinutes("$date 09:00:00")
                        : 0;

                    return [
                        'date' => \Carbon\Carbon::parse($record->created_at)->format('M d, Y'),
                        'time' => \Carbon\Carbon::parse($record->time_in)->format('h:i A'),
                        'late_minutes' => $lateMinutes,
                    ];
                } catch (\Exception $e) {
                    \Log::error('Invalid date or time for attendance record', ['record' => $record, 'error' => $e->getMessage()]);
                    return null;
                }
            })
            ->filter();
        }

        $employee_number = auth()->user()->employee->employee_number;

        $start = Carbon::now()->startOfMonth();
        $today = Carbon::now()->startOfDay();

        $period = CarbonPeriod::create($start, $today);

        $absentDates = [];

        foreach ($period as $date) {
            if ($date->isWeekend()) {
                continue;
            }

            if (Holiday::whereDate('holiday_date', $date)->exists()) {
                continue;
            }

            $hasDtr = Attendance::where('employee_code', $employee_number)
                        ->whereDate('time_in', $date)
                        ->exists();

            $hasLeave = EmployeeLeave::where('user_id', auth()->id())
                        ->where('status', 'Approved')
                        ->whereDate('date_from', '<=', $date)
                        ->whereDate('date_to', '>=', $date)
                        ->exists();

            if (!$hasDtr && !$hasLeave) {
                $absentDates[] = $date->format('Y-m-d');
            }
        }

        $header = 'leave_calendar';

        $leave_plans_per_month = LeavePlan::where('department_id', auth()->user()->employee->department_id)
            ->whereYear('date_from', date('Y'))
            ->whereMonth('date_to', date('m'))
            ->get();
        $leave_plan_array = [];
        $leave_plans = LeavePlan::where('department_id', auth()->user()->employee->department_id)->get();
        foreach($leave_plans as $leave_plan)
        {
            $object = new stdClass;
            $object->title = $leave_plan->reason;
            $object->start = date('Y-m-d h:i:s', strtotime($leave_plan->date_from));
            $object->end = date('Y-m-d h:i:s', strtotime($leave_plan->date_to));
            $object->color = '#57B657';
            $object->leave_calendar_id = $leave_plan->id;
            $object->reason = $leave_plan->reason;
            $object->date_from = $leave_plan->date_from;
            $object->date_to = $leave_plan->date_to;
            $leave_plan_array[] = $object;
        }

        $latePerDay = [];

        if (auth()->user()->employee && auth()->user()->employee->employee_number) {
            $employeeNumber = auth()->user()->employee->employee_number;

            $today = Carbon::today();
            $year = $today->year;
            $month = $today->month;

            // Determine the fixed date range
            if ($today->day >= 6 && $today->day <= 20) {
                // Fixed range: 6 to 20 of current month
                $startDate = Carbon::create($year, $month, 6);
                $endDate = Carbon::create($year, $month, 20);
            } else {
                // Fixed range: 21 to 5 (spanning months)
                if ($today->day >= 21) {
                    // From 21 of current month to 5 of next month
                    $startDate = Carbon::create($year, $month, 21);
                    $endDate = Carbon::create($year, $month, 5)->addMonth();
                } else {
                    // From 21 of previous month to 5 of current month
                    $startDate = Carbon::create($year, $month, 21)->subMonth();
                    $endDate = Carbon::create($year, $month, 5);
                }
            }

            // Collect dates within the fixed range
            $dates = collect();
            $current = $startDate->copy();
            while ($current->lte($endDate)) {
                $dates->push($current->copy());
                $current->addDay();
            }

            // Loop through each date and calculate late minutes
            foreach ($dates as $date) {
                $attendance = Attendance::where('employee_code', $employeeNumber)
                    ->whereDate('created_at', $date->toDateString())
                    ->orderBy('time_in', 'asc')
                    ->first();

                $lateMinutes = 0;

                if ($attendance && $attendance->time_in) {
                    $timeIn = Carbon::parse($attendance->time_in);
                    $expectedTime = Carbon::parse($date->format('Y-m-d') . ' 09:00:00');

                    if ($timeIn->gt($expectedTime)) {
                        $lateMinutes = $timeIn->diffInMinutes($expectedTime);
                    }
                }

                // Format date as "June 6"
                $displayDate = $date->format('F j');

                $latePerDay[] = [
                    'date' => $displayDate,
                    'late_minutes' => $lateMinutes,
                ];
            }
        }

        // for admin side
        $locations = Employee::whereIn('status', ['Active', 'HBU'])
                            ->pluck('location')
                            ->unique()
                            ->sort()
                            ->values();

        $today = Carbon::today()->toDateString();
        $tenAM = Carbon::today()->setTime(10, 0);

        $all_employees = Employee::whereIn('status', ['Active', 'HBU'])->get();
        $total_employees = $all_employees->count();


        $present_today_count = Attendance::whereDate('time_in', $today)
            ->whereHas('employee', function($query) {
                $query->whereIn('status', ['Active', 'HBU']);
            })
            ->distinct('employee_code')
            ->count('employee_code');


        $activeEmployees = Employee::whereIn('status', ['Active', 'HBU'])->get();

        $absentTodayCount = $activeEmployees->filter(function ($employee) use ($tenAM) {
            if (!$employee->employee_number) return false;

            $attendance = Attendance::where('employee_code', $employee->employee_number)
                ->whereDate('time_in', now()->toDateString())
                ->exists();

            return !$attendance && now()->greaterThan($tenAM);
        })->count();

        $lateComersCount = Attendance::whereDate('time_in', $today)
            ->whereTime('time_in', '>', '09:00:00')
            ->whereHas('employee', function($query) {
                $query->whereIn('status', ['Active', 'HBU']);
            })
            ->distinct('employee_code')
            ->count('employee_code');


        return view('dashboards.home',
        array(
            'header' => '',
            'date_ranges' => $date_ranges,
            'handbook' => $handbook,
            'attendance_now' => $attendance_now,
            'attendances' => $attendances,
            'schedules' => $schedules,
            'announcements' => $announcements ,
            'attendance_employees' => $attendance_employees ,
            'holidays' => $holidays ,
            'employee_birthday_celebrants' => $employee_birthday_celebrants ,
            'employees_new_hire' => $employees_new_hire ,
            'employee_anniversaries' => $employee_anniversaries,
            'probationary_employee' => $probationary_employee,
            'classifications' =>$classifications,
            'leaveTypes' => $leaveTypes,
            'documents' => $documents,
            'usedLeaves' => $usedLeaves,
            'totalUsedLeaveDays' => $totalUsedLeaveDays, 
            'lateRecords' => $lateRecords,
            'absentDates' => $absentDates,
              'latePerDay' => $latePerDay,
            'leave_plans_per_month' => $leave_plans_per_month,
            'leave_plan_array' => $leave_plan_array,
            'total_employees' => $total_employees,
            'present_today_count' => $present_today_count,
            'absent_today_count' => $absentTodayCount,
            'late_comers_count' => $lateComersCount,
            'locations' => $locations,

        ));
    }


        public function filterByLocation(Request $request)
    {
        $location = $request->input('location');
        $today = \Carbon\Carbon::today()->toDateString();

        $employees = \App\Employee::whereIn('status', ['Active', 'HBU']);
        if ($location) {
            $employees->where('location', $location);
        }

        $employeeCodes = $employees->pluck('employee_number')->filter();

        $total_employees = $employeeCodes->count();

        $present_today_count = \App\Attendance::whereDate('time_in', $today)
            ->whereIn('employee_code', $employeeCodes)
            ->distinct('employee_code')
            ->count('employee_code');

        $tenAM = \Carbon\Carbon::today()->setTime(10, 0);

        $absent_today_count = $employees->get()->filter(function ($employee) use ($tenAM) {
            if (!$employee->employee_number) return false;

            $attendance = \App\Attendance::where('employee_code', $employee->employee_number)
                ->whereDate('time_in', now()->toDateString())
                ->exists();

            return !$attendance && now()->greaterThan($tenAM);
        })->count();

        $late_comers_count = \App\Attendance::whereDate('time_in', $today)
            ->whereTime('time_in', '>', '09:00:00')
            ->whereIn('employee_code', $employeeCodes)
            ->distinct('employee_code')
            ->count('employee_code');

        return response()->json([
            'total_employees' => $total_employees,
            'present_today_count' => $present_today_count,
            'absent_today_count' => $absent_today_count,
            'late_comers_count' => $late_comers_count,
        ]);
    }

    public function absenteesPie(Request $request)
    {
        $location = $request->input('location');
        $today = Carbon::today();

        $dates = collect();
        $day = $today->copy();
        while ($dates->count() < 7) {
            if (!$day->isWeekend()) {
                $dates->push($day->copy());
            }
            $day->subDay();
        }

        $employees = Employee::whereIn('status', ['Active', 'HBU']);
        if ($location) {
            $employees->where('location', $location);
        }
        $employeesList = $employees->get();

        $absenteesCounts = [];

        foreach ($dates as $date) {
            $absentCount = $employeesList->filter(function ($employee) use ($date) {
                if (!$employee->employee_number) return false;

                $hasAttendance = Attendance::whereDate('time_in', $date)
                    ->where('employee_code', $employee->employee_number)
                    ->exists();

                $hasLeave = EmployeeLeave::where('user_id', $employee->user_id)
                    ->where('status', 'Approved')
                    ->whereDate('date_from', '<=', $date)
                    ->whereDate('date_to', '>=', $date)
                    ->exists();

                return !$hasAttendance && !$hasLeave;
            })->count();

            $absenteesCounts[] = $absentCount;
        }

        $totalAbsents = array_sum($absenteesCounts);
        $percentages = $totalAbsents > 0
            ? array_map(fn($count) => ($count / $totalAbsents) * 100, $absenteesCounts)
            : array_fill(0, count($absenteesCounts), 0);

        return response()->json([
            'labels' => $dates->map(fn($date) => $date->format('M d'))->reverse()->values(),
            'percentages' => array_reverse($percentages),
        ]);
    }

   public function absenteesMonthlyPie(Request $request)
    {
        $location = $request->input('location');
        $today = Carbon::today();

        $months = collect();
        for ($i = 3; $i >= 1; $i--) {
            $months->push($today->copy()->subMonthsNoOverflow($i)->startOfMonth());
        }

        // Get employees
        $employeesQuery = Employee::whereIn('status', ['Active', 'HBU']);
        if ($location) {
            $employeesQuery->where('location', $location);
        }
        $employees = $employeesQuery->get();
        $employeeIds = $employees->pluck('id');
        $employeeCodes = $employees->pluck('employee_number');

        if ($employees->isEmpty()) {
            return response()->json([
                'labels' => $months->map(fn($m) => $m->format('M'))->values(),
                'percentages' => [0, 0, 0],
            ]);
        }

        $startDate = $months->first()->copy()->startOfMonth();
        $endDate = $months->last()->copy()->endOfMonth();

        $attendances = Attendance::whereBetween('time_in', [$startDate, $endDate])
            ->whereIn('employee_code', $employeeCodes)
            ->get()
            ->groupBy(fn($a) => Carbon::parse($a->time_in)->format('Y-m-d'));

        $leaves = EmployeeLeave::where('status', 'Approved')
            ->whereIn('user_id', $employeeIds)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date_from', [$startDate, $endDate])
                ->orWhereBetween('date_to', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('date_from', '<=', $startDate)
                            ->where('date_to', '>=', $endDate);
                });
            })
            ->get();

        $leaveMap = [];
        foreach ($leaves as $leave) {
            $period = CarbonPeriod::create(
                Carbon::parse($leave->date_from),
                Carbon::parse($leave->date_to)
            );

            foreach ($period as $day) {
                $dateStr = $day->format('Y-m-d');
                $leaveMap[$dateStr][$leave->user_id] = true;
            }
        }

        $monthlyData = [];

        foreach ($months as $monthStart) {
            $monthLabel = $monthStart->format('M');
            $monthEnd = $monthStart->copy()->endOfMonth();

            $dates = collect();
            $date = $monthStart->copy();
            while ($date->lte($monthEnd)) {
                if (!$date->isWeekend()) {
                    $dates->push($date->copy());
                }
                $date->addDay();
            }

            $totalAbsent = 0;
            $totalPossible = $employees->count() * $dates->count();

            foreach ($dates as $day) {
                $dateStr = $day->format('Y-m-d');
                $attendanceForDay = $attendances->get($dateStr, collect());
                $presentCodes = $attendanceForDay->pluck('employee_code')->flip();

                foreach ($employees as $emp) {
                    $code = $emp->employee_number;
                    $uid = $emp->id;

                    $hasAttendance = $presentCodes->has($code);
                    $hasLeave = isset($leaveMap[$dateStr][$uid]);

                    if (!$hasAttendance && !$hasLeave) {
                        $totalAbsent++;
                    }
                }
            }

            $monthlyData[] = [
                'label' => $monthLabel,
                'percentage' => $totalPossible > 0 ? round(($totalAbsent / $totalPossible) * 100, 2) : 0,
            ];
        }

        return response()->json([
            'labels' => collect($monthlyData)->pluck('label'),
            'percentages' => collect($monthlyData)->pluck('percentage'),
        ]);
    }


   public function latePie(Request $request)
    {
        $location = $request->input('location');
        $today = Carbon::today();

        // Step 1: Last 7 working days (excluding weekends)
        $dates = collect();
        $day = $today->copy();
        while ($dates->count() < 7) {
            if (!$day->isWeekend()) {
                $dates->push($day->copy());
            }
            $day->subDay();
        }
        $dates = $dates->reverse();  // chronological order

        // Step 2: Get employees filtered by location
        $employeesQuery = Employee::whereIn('status', ['Active', 'HBU']);
        if ($location) {
            $employeesQuery->where('location', $location);
        }
        $employeesList = $employeesQuery->get();

        $employeeNumbers = $employeesList->pluck('employee_number')->filter()->all();
        if (empty($employeeNumbers)) {
            // No employees, return empty result early
            return response()->json([
                'labels' => $dates->map(fn($date) => $date->format('M d'))->values(),
                'counts' => array_fill(0, $dates->count(), 0),
            ]);
        }

        // Step 3: Bulk fetch attendance for all employees on these dates
        $startDate = $dates->first()->format('Y-m-d');
        $endDate = $dates->last()->format('Y-m-d');

        $attendances = Attendance::whereIn('employee_code', $employeeNumbers)
            ->whereDate('time_in', '>=', $startDate)
            ->whereDate('time_in', '<=', $endDate)
            ->get()
            ->groupBy(function($attendance) {
                return Carbon::parse($attendance->time_in)->format('Y-m-d') . '|' . $attendance->employee_code;
            });

        // Step 4: Count late check-ins per date
        $lateCounts = [];
        foreach ($dates as $date) {
            $lateThreshold = $date->copy()->setTime(9, 0, 0);
            $lateCount = 0;

            foreach ($employeeNumbers as $empNum) {
                $key = $date->format('Y-m-d') . '|' . $empNum;
                if (!isset($attendances[$key])) {
                    continue;
                }
                // If multiple records, check any late
                $attendanceRecords = $attendances[$key];
                foreach ($attendanceRecords as $attendance) {
                    $timeIn = Carbon::parse($attendance->time_in);
                    if ($timeIn->gt($lateThreshold)) {
                        $lateCount++;
                        break; // count once per employee per day
                    }
                }
            }
            $lateCounts[] = $lateCount;
        }

        return response()->json([
            'labels' => $dates->map(fn($date) => $date->format('M d'))->values(),
            'counts' => $lateCounts,
        ]);
    }


    public function managerDashboard()
    { 
        $handbook = Handbook::orderBy('id','desc')->first();
        return view('dashboards.dashboard_manager',
        array(
            'header' => 'dashboard-manager',
            'handbook' => $handbook,
        ));
    }

    public function pending_leave_count($approver_id){

        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeLeave::select('user_id')->with('approver.approver_info')
                                    ->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->where('status','Pending')
                                    // ->whereDate('created_at','>=',$from_date)
                                    // ->whereDate('created_at','<=',$to_date)
                                    ->count();
    }
    public function pending_overtime_count($approver_id){
    
        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeOvertime::select('user_id')->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->where('status','Pending')
                                    // ->whereDate('created_at','>=',$from_date)
                                    // ->whereDate('created_at','<=',$to_date)
                                    ->count();
    }
    public function edit_prob(Request $request, $id) {
        $employee = Employee::findOrFail($id);
    
        $classification = $request->input('classification');
    
        if ($classification) {
            if ($classification == 'for_regularization') {
                $employee->classification = '2';
                $employee->date_regularized = $request->input('date_regular');

                // $leave_credit = EmployeeLeaveCredit::where('user_id',$id)
                //                             ->where('leave_type',$request->leave_type)
                //                             ->first();
                // if($leave_credit){
                //     $leave_credit->count = $request->count;
                //     $leave_credit->save();
                // }else{
                //     $leave_credit = new EmployeeLeaveCredit;
                //     $leave_credit->leave_type = $request->leave_type;
                //     $leave_credit->user_id = $id;
                //     $leave_credit->count = $request->count;
                //     $leave_credit->save();
                // }

                $leave_credit_sick = EmployeeLeaveCredit::where('user_id', $id)
                                                ->where('leave_type', '2')
                                                ->first();
                if ($leave_credit_sick) {
                    $leave_credit_sick->count = $request->input('sl_count');
                    $leave_credit_sick->save();
                } else {
                    $leave_credit_sick = new EmployeeLeaveCredit;
                    $leave_credit_sick->leave_type = '2';
                    $leave_credit_sick->user_id = $id;
                    $leave_credit_sick->count = $request->input('sl_count');
                    $leave_credit_sick->save();
                }

                $leave_credit_vacation = EmployeeLeaveCredit::where('user_id', $id)
                                                    ->where('leave_type', '1')
                                                    ->first();
                if ($leave_credit_vacation) {
                    $leave_credit_vacation->count = $request->input('vl_count');
                    $leave_credit_vacation->save();
                } else {
                    $leave_credit_vacation = new EmployeeLeaveCredit;
                    $leave_credit_vacation->leave_type = '1';
                    $leave_credit_vacation->user_id = $id;
                    $leave_credit_vacation->count = $request->input('vl_count');
                    $leave_credit_vacation->save();
                }

            } elseif ($classification == 'for_resignation') {
                $employee->status = 'Inactive';
                $employee->date_resigned = $request->input('date_resigned');
            }
    
            $employee->save();
            Alert::success('Successfully Updated')->persistent('Dismiss');
            return back();
        } else {
            return back()->withErrors(['classification' => 'Classification is required.']);


        }
    }
    
    public function pending_wfh_count($approver_id){
    
        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeWfh::select('user_id')->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->where('status','Pending')
                                    // ->whereDate('created_at','>=',$from_date)
                                    // ->whereDate('created_at','<=',$to_date)
                                    ->count();
    }
    
    public function pending_ob_count($approver_id){
    
        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeOb::select('user_id')->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->where('status','Pending')
                                    // ->whereDate('created_at','>=',$from_date)
                                    // ->whereDate('created_at','<=',$to_date)
                                    ->count();
    }
    
    public function pending_dtr_count($approver_id){
    
        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeDtr::select('user_id')->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->where('status','Pending')
                                    // ->whereDate('created_at','>=',$from_date)
                                    // ->whereDate('created_at','<=',$to_date)
                                    ->count();
    }
}
