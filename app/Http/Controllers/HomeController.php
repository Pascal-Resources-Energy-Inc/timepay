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
use Illuminate\Support\Facades\DB;



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
            
            $scheduleId = auth()->user()->employee->schedule_id;
            
            $schedule = DB::table('schedule_datas')->where('schedule_id', $scheduleId)->first();
            
            if ($schedule) {
                $expectedTimeIn = $schedule->time_in_from;
                
                $lateRecords = Attendance::where('employee_code', $employeeNumber)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->orderBy('time_in', 'asc')
                    ->get(['time_in', 'created_at'])
                    ->map(function ($record) use ($expectedTimeIn) {
                        if (is_null($record->created_at) || is_null($record->time_in)) {
                            return null;
                        }

                        try {
                            $date = \Carbon\Carbon::parse($record->created_at)->format('Y-m-d');
                            $time = \Carbon\Carbon::parse($record->time_in)->format('H:i:s');
                            $datetime = \Carbon\Carbon::parse("$date $time");

                            $expectedDateTime = \Carbon\Carbon::parse("$date $expectedTimeIn");
                            
                            $actualTimeMinutes = $datetime->format('H:i');
                            $expectedTimeMinutes = $expectedDateTime->format('H:i');
                            
                            // Check if late by comparing only hours and minutes
                            $isLate = $actualTimeMinutes > $expectedTimeMinutes;
                            
                            if ($isLate) {
                                $lateMinutes = $expectedDateTime->diffInMinutes($datetime);
                                
                                return [
                                    'date' => \Carbon\Carbon::parse($record->created_at)->format('M d, Y'),
                                    'time' => \Carbon\Carbon::parse($record->time_in)->format('h:i A'),
                                    'late_minutes' => $lateMinutes,
                                    'expected_time' => \Carbon\Carbon::parse($expectedTimeIn)->format('h:i A'),
                                ];
                            }
                            
                            return null;
                        } catch (\Exception $e) {
                            return null;
                        }
                    })
                    ->filter();
            } else {
                $lateRecords = collect();
            }
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
            $scheduleId = auth()->user()->employee->schedule_id;
            $schedule = DB::table('schedule_datas')->where('schedule_id', $scheduleId)->first();

            if ($schedule) {
                $expectedTimeString = $schedule->time_in_from;
                $today = Carbon::today();
                $year = $today->year;
                $month = $today->month;

                if ($today->day >= 6 && $today->day <= 20) {
                    $startDate = Carbon::create($year, $month, 6);
                    $endDate = Carbon::create($year, $month, 20);
                } else {
                    if ($today->day >= 21) {
                        $startDate = Carbon::create($year, $month, 21);
                        $endDate = Carbon::create($year, $month, 5)->addMonth();
                    } else {
                        $startDate = Carbon::create($year, $month, 21)->subMonth();
                        $endDate = Carbon::create($year, $month, 5);
                    }
                }

                $dates = collect();
                $current = $startDate->copy();
                while ($current->lte($endDate)) {
                    $dates->push($current->copy());
                    $current->addDay();
                }

                foreach ($dates as $date) {
                    if ($date->dayOfWeek === 0) {
                        continue;
                    }

                    if (Holiday::whereDate('holiday_date', $date)->exists()) {
                        continue;
                    }

                    $attendance = Attendance::where('employee_code', $employeeNumber)
                        ->whereDate('created_at', $date->toDateString())
                        ->orderBy('time_in', 'asc')
                        ->first();

                    $lateMinutes = 0;
                    $status = 'Present';

                    if ($attendance && $attendance->time_in) {
                        $timeIn = Carbon::parse($attendance->time_in);
                        $expectedTime = Carbon::parse($date->format('Y-m-d') . ' ' . $expectedTimeString);

                        if ($timeIn->gt($expectedTime)) {
                            $lateMinutes = $expectedTime->diffInMinutes($timeIn);
                        }
                    } else {
                        $hasLeave = EmployeeLeave::where('user_id', auth()->id())
                            ->where('status', 'Approved')
                            ->whereDate('date_from', '<=', $date)
                            ->whereDate('date_to', '>=', $date)
                            ->exists();

                        if (!$hasLeave && $date->lt($today)) {
                            $status = 'Absent';
                        } elseif (!$hasLeave && $date->isSameDay($today)) {
                            $status = 'No Attendance';
                        } else {
                            $status = 'Present';
                        }
                    }

                    $latePerDay[] = [
                        'date' => $date->format('F j'),
                        'late_minutes' => $lateMinutes,
                        'status' => $status,
                        'expected_time' => Carbon::parse($expectedTimeString)->format('g:i A'),
                        'actual_time_in' => $attendance && $attendance->time_in
                            ? Carbon::parse($attendance->time_in)->format('g:i A')
                            : ($status === 'Absent' ? 'Absent' : ($status === 'No Attendance' ? 'No attendance' : '')),
                    ];
                }
            } else {
                $latePerDay = [];
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

       $lateComersCount = 0;
        try {
            // Get all active employees with their schedules and attendance in one query
            $lateEmployees = DB::table('employees')
                ->join('schedule_datas', 'employees.schedule_id', '=', 'schedule_datas.schedule_id')
                ->join('attendances', 'employees.employee_number', '=', 'attendances.employee_code')
                ->whereIn('employees.status', ['Active', 'HBU'])
                ->whereDate('attendances.time_in', $today)
                ->whereRaw('TIME(attendances.time_in) > TIME( 
                            CASE 
                                WHEN LENGTH(TRIM(schedule_datas.time_in_from)) = 5 
                                THEN ADDTIME(CONCAT(TRIM(schedule_datas.time_in_from), ":00"), "00:01:00")
                                ELSE ADDTIME(TRIM(schedule_datas.time_in_from), "00:01:00") 
                            END
                          )')
                ->select('employees.employee_number')
                ->distinct()
                ->get();
            
            $lateComersCount = $lateEmployees->count();
            
        } catch (\Exception $e) {
            \Log::error('Error calculating late comers count', [
                'error' => $e->getMessage(),
                'date' => $today
            ]);
            $lateComersCount = 0;
        }


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

        // Fixed late comers count - using the same logic as your working code
        $late_comers_count = 0;
        try {
            $lateEmployees = DB::table('employees')
                ->join('schedule_datas', 'employees.schedule_id', '=', 'schedule_datas.schedule_id')
                ->join('attendances', 'employees.employee_number', '=', 'attendances.employee_code')
                ->whereIn('employees.status', ['Active', 'HBU'])
                ->whereIn('employees.employee_number', $employeeCodes) // Added location filter
                ->whereDate('attendances.time_in', $today)
                ->whereRaw('TIME(attendances.time_in) > TIME( 
                            CASE 
                                WHEN LENGTH(TRIM(schedule_datas.time_in_from)) = 5 
                                THEN ADDTIME(CONCAT(TRIM(schedule_datas.time_in_from), ":00"), "00:01:00")
                                ELSE ADDTIME(TRIM(schedule_datas.time_in_from), "00:01:00") 
                            END
                          )')
                ->select('employees.employee_number')
                ->distinct()
                ->get();
            
            $late_comers_count = $lateEmployees->count();
            
        } catch (\Exception $e) {
            \Log::error('Error calculating late comers count in filterByLocation', [
                'error' => $e->getMessage(),
                'date' => $today,
                'location' => $location
            ]);
            $late_comers_count = 0;
        }

        return response()->json([
            'total_employees' => $total_employees,
            'present_today_count' => $present_today_count,
            'absent_today_count' => $absent_today_count,
            'late_comers_count' => $late_comers_count,
        ]);
    }
    
    public function getEmployees(Request $request)
    {
        try {
            $location = $request->input('location');
            
            // Start with basic query
            $query = Employee::whereIn('status', ['Active', 'HBU']);
            
            // Add location filter if provided
            if ($location && $location !== '') {
                $query->where('location', $location);
            }
            
            // Get employees with required fields
            $employees = $query->select([
                    'employee_number', 
                    'first_name', 
                    'middle_name', 
                    'last_name', 
                    'location'
                ])
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

            // Log for debugging
            \Log::info('getEmployees called successfully', [
                'location_filter' => $location,
                'employee_count' => $employees->count(),
                'sample_employee' => $employees->first()
            ]);

            return response()->json([
                'success' => true,
                'employees' => $employees,
                'total' => $employees->count(),
                'location_filter' => $location
            ]);
            
        } catch (\Exception $e) {
            // Log the actual error
            \Log::error('Error in getEmployees method', [
                'error_message' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'location_filter' => $request->input('location')
            ]);
            
            return response()->json([
                'success' => false,
                'employees' => [],
                'total' => 0,
                'error' => 'Failed to load employees: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPresentEmployees(Request $request)
    {
        try {
            $location = $request->input('location');
            $today = \Carbon\Carbon::today()->toDateString();
            
            // Optimized query to get present employees with their earliest time_in
            $query = Employee::whereIn('status', ['Active', 'HBU'])
                ->join('attendances', 'employees.employee_number', '=', 'attendances.employee_code')
                ->whereDate('attendances.time_in', $today)
                ->select([
                    'employees.employee_number',
                    'employees.first_name',
                    'employees.middle_name', 
                    'employees.last_name',
                    'employees.location',
                    DB::raw('MIN(attendances.time_in) as earliest_time_in')
                ])
                ->groupBy([
                    'employees.employee_number',
                    'employees.first_name', 
                    'employees.middle_name',
                    'employees.last_name',
                    'employees.location'
                ]);
            
            if ($location) {
                $query->where('employees.location', $location);
            }
            
            $employees = $query->orderBy('employees.first_name')
                            ->orderBy('employees.last_name')
                            ->get()
                            ->map(function($employee) {
                                return [
                                    'employee_number' => $employee->employee_number,
                                    'first_name' => $employee->first_name,
                                    'middle_name' => $employee->middle_name,
                                    'last_name' => $employee->last_name,
                                    'location' => $employee->location,
                                    'time_in' => \Carbon\Carbon::parse($employee->earliest_time_in)->format('h:i A'),
                                    'time_in_raw' => $employee->earliest_time_in,
                                ];
                            });

            return response()->json([
                'success' => true,
                'employees' => $employees,
                'total' => $employees->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'employees' => [],
                'total' => 0,
                'error' => 'Failed to load present employees'
            ], 500);
        }
    }

    public function getAbsentEmployees(Request $request)
        {
            try {
                $location = $request->input('location');
                $today = \Carbon\Carbon::today()->toDateString();
                $tenAM = \Carbon\Carbon::today()->setTime(10, 0);
                
                // Only show absent employees if it's after 10 AM
                if (now()->lessThan($tenAM)) {
                    return response()->json([
                        'success' => true,
                        'employees' => [],
                        'total' => 0,
                        'message' => 'Absent employees will be shown after 10:00 AM'
                    ]);
                }
                
                // Get all active employees
                $query = Employee::whereIn('status', ['Active', 'HBU']);
                
                if ($location) {
                    $query->where('location', $location);
                }
                
                $allEmployees = $query->select([
                    'employee_number',
                    'first_name', 
                    'middle_name',
                    'last_name',
                    'location'
                ])->get();
                
                // Filter out employees who have attendance today or approved leave
                $absentEmployees = $allEmployees->filter(function($employee) use ($today) {
                    if (!$employee->employee_number) {
                        return false;
                    }
                    
                    // Check if employee has attendance today
                    $hasAttendance = DB::table('attendances')
                        ->where('employee_code', $employee->employee_number)
                        ->whereDate('time_in', $today)
                        ->exists();
                    
                    // Check if employee has approved leave today
                    $hasLeave = DB::table('employee_leaves')
                        ->join('users', 'employee_leaves.user_id', '=', 'users.id')
                        ->join('employees', 'users.id', '=', 'employees.user_id')
                        ->where('employees.employee_number', $employee->employee_number)
                        ->where('employee_leaves.status', 'Approved')
                        ->whereDate('employee_leaves.date_from', '<=', $today)
                        ->whereDate('employee_leaves.date_to', '>=', $today)
                        ->exists();
                    
                    // Employee is absent if no attendance and no approved leave
                    return !$hasAttendance && !$hasLeave;
                });
                
                // Transform to array format
                $absentEmployeesArray = $absentEmployees->map(function($employee) {
                    return [
                        'employee_number' => $employee->employee_number,
                        'first_name' => $employee->first_name,
                        'middle_name' => $employee->middle_name,
                        'last_name' => $employee->last_name,
                        'location' => $employee->location,
                    ];
                })->values();

                return response()->json([
                    'success' => true,
                    'employees' => $absentEmployeesArray,
                    'total' => $absentEmployeesArray->count()
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'employees' => [],
                    'total' => 0,
                    'error' => 'Failed to load absent employees'
                ], 500);
            }
        }

    public function getLateEmployees(Request $request)
        {
            try {
                $location = $request->input('location');
                $today = \Carbon\Carbon::today()->toDateString();
                
                $query = DB::table('employees')
                    ->join('schedule_datas', 'employees.schedule_id', '=', 'schedule_datas.schedule_id')
                    ->join('attendances', 'employees.employee_number', '=', 'attendances.employee_code')
                    ->whereIn('employees.status', ['Active', 'HBU'])
                    ->whereDate('attendances.time_in', $today)
                    ->whereRaw('TIME(attendances.time_in) > TIME( 
                                CASE 
                                    WHEN LENGTH(TRIM(schedule_datas.time_in_from)) = 5 
                                    THEN ADDTIME(CONCAT(TRIM(schedule_datas.time_in_from), ":00"), "00:01:00")
                                    ELSE ADDTIME(TRIM(schedule_datas.time_in_from), "00:01:00") 
                                END
                            )')
                    ->select([
                        'employees.employee_number',
                        'employees.first_name',
                        'employees.middle_name', 
                        'employees.last_name',
                        'employees.location',
                        'schedule_datas.time_in_from as expected_time',
                        DB::raw('MIN(attendances.time_in) as actual_time_in')
                    ])
                    ->groupBy([
                        'employees.employee_number',
                        'employees.first_name', 
                        'employees.middle_name',
                        'employees.last_name',
                        'employees.location',
                        'schedule_datas.time_in_from'
                    ]);
                
                if ($location) {
                    $query->where('employees.location', $location);
                }
                
                $lateEmployees = $query->orderBy('employees.first_name')
                                    ->orderBy('employees.last_name')
                                    ->get()
                                    ->map(function($employee) use ($today) {

                                        $expectedTime = \Carbon\Carbon::parse($today . ' ' . $employee->expected_time);
                                        $actualTime = \Carbon\Carbon::parse($employee->actual_time_in);
                                        $lateMinutes = $actualTime->diffInMinutes($expectedTime);
                                        
                                        $expectedTimeFormatted = \Carbon\Carbon::parse($employee->expected_time)->format('h:i A');
                                        $actualTimeFormatted = $actualTime->format('h:i A');
                                        
                                        $lateDuration = '';
                                        if ($lateMinutes >= 60) {
                                            $hours = floor($lateMinutes / 60);
                                            $minutes = $lateMinutes % 60;
                                            $lateDuration = $hours . 'h ' . $minutes . 'm late';
                                        } else {
                                            $lateDuration = $lateMinutes . 'm late';
                                        }
                                        
                                        return [
                                            'employee_number' => $employee->employee_number,
                                            'first_name' => $employee->first_name,
                                            'middle_name' => $employee->middle_name,
                                            'last_name' => $employee->last_name,
                                            'location' => $employee->location,
                                            'expected_time' => $expectedTimeFormatted,
                                            'actual_time_in' => $actualTimeFormatted,
                                            'late_minutes' => $lateMinutes,
                                            'late_duration' => $lateDuration,
                                        ];
                                    });

                return response()->json([
                    'success' => true,
                    'employees' => $lateEmployees,
                    'total' => $lateEmployees->count()
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'employees' => [],
                    'total' => 0,
                    'error' => 'Failed to load late employees'
                ], 500);
            }
        }

    public function absenteesPie(Request $request)
        {
            $location = $request->input('location');
            $today = Carbon::today();

            $dates = [];
            $day = $today->copy();
            while (count($dates) < 7) {
                if (!$day->isWeekend()) {
                    array_unshift($dates, $day->copy()); 
                }
                $day->subDay();
            }

            $employeesQuery = Employee::select('user_id', 'employee_number')
                ->whereIn('status', ['Active', 'HBU'])
                ->whereNotNull('employee_number');
            
            if ($location) {
                $employeesQuery->where('location', $location);
            }
            
            $employees = $employeesQuery->get();
            
            if ($employees->isEmpty()) {
                return response()->json([
                    'labels' => array_map(fn($date) => $date->format('M d'), $dates),
                    'counts' => array_fill(0, 7, 0),
                ]);
            }

            $employeeCodes = $employees->pluck('employee_number')->toArray();
            $userIds = $employees->pluck('user_id')->toArray();
            $employeeMap = $employees->keyBy('employee_number');

            $startDate = $dates[0]->format('Y-m-d');
            $endDate = $dates[6]->format('Y-m-d');
            
            $attendanceRecords = Attendance::select('employee_code', 'time_in')
                ->whereIn('employee_code', $employeeCodes)
                ->whereDate('time_in', '>=', $startDate)
                ->whereDate('time_in', '<=', $endDate)
                ->get()
                ->groupBy(function($record) {
                    return Carbon::parse($record->time_in)->format('Y-m-d') . '|' . $record->employee_code;
                });

            $leaveRecords = EmployeeLeave::select('user_id', 'date_from', 'date_to')
                ->whereIn('user_id', $userIds)
                ->where('status', 'Approved')
                ->where('date_from', '<=', $endDate)
                ->where('date_to', '>=', $startDate)
                ->get();

            $leaveMap = [];
            foreach ($leaveRecords as $leave) {
                $dateFrom = Carbon::parse($leave->date_from);
                $dateTo = Carbon::parse($leave->date_to);
                
                $current = max($dateFrom, Carbon::parse($startDate));
                $end = min($dateTo, Carbon::parse($endDate));
                
                while ($current->lte($end)) {
                    if (!$current->isWeekend()) {
                        $leaveMap[$current->format('Y-m-d')][$leave->user_id] = true;
                    }
                    $current->addDay();
                }
            }

            $absenteesCounts = [];
            foreach ($dates as $date) {
                $dateStr = $date->format('Y-m-d');
                $absentCount = 0;

                foreach ($employeeCodes as $empCode) {
                    $attendanceKey = $dateStr . '|' . $empCode;
                    $userId = $employeeMap[$empCode]->user_id;
                    
                    if (!isset($attendanceRecords[$attendanceKey]) && 
                        !isset($leaveMap[$dateStr][$userId])) {
                        $absentCount++;
                    }
                }
                
                $absenteesCounts[] = $absentCount;
            }

            return response()->json([
                'labels' => array_map(fn($date) => $date->format('M d'), $dates),
                'counts' => $absenteesCounts,
            ]);
        }

    public function absenteesMonthlyPie(Request $request)
        {
            $location = $request->input('location');
            $today = Carbon::today();

            // Generate last 3 months
            $months = collect();
            for ($i = 3; $i >= 1; $i--) {
                $months->push($today->copy()->subMonthsNoOverflow($i)->startOfMonth());
            }

            // Get employees with optimized query
            $employeesQuery = Employee::select('id', 'user_id', 'employee_number')
                ->whereIn('status', ['Active', 'HBU'])
                ->whereNotNull('employee_number');
            
            if ($location) {
                $employeesQuery->where('location', $location);
            }
            
            $employees = $employeesQuery->get();

            if ($employees->isEmpty()) {
                return response()->json([
                    'labels' => $months->map(fn($m) => $m->format('M'))->values(),
                    'percentages' => [0, 0, 0],
                ]);
            }

            $employeeCodes = $employees->pluck('employee_number')->toArray();
            $userIds = $employees->pluck('user_id')->toArray();

            $startDate = $months->first()->startOfMonth();
            $endDate = $months->last()->endOfMonth();

            // Bulk fetch attendance records
            $attendanceRecords = Attendance::select('employee_code', 'time_in')
                ->whereIn('employee_code', $employeeCodes)
                ->whereDate('time_in', '>=', $startDate)
                ->whereDate('time_in', '<=', $endDate)
                ->get()
                ->groupBy(function($record) {
                    return Carbon::parse($record->time_in)->format('Y-m-d') . '|' . $record->employee_code;
                });

            // Bulk fetch leave records
            $leaveRecords = EmployeeLeave::select('user_id', 'date_from', 'date_to')
                ->whereIn('user_id', $userIds)
                ->where('status', 'Approved')
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date_from', [$startDate, $endDate])
                    ->orWhereBetween('date_to', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('date_from', '<=', $startDate)
                                ->where('date_to', '>=', $endDate);
                    });
                })
                ->get();

            // Build leave lookup map
            $leaveMap = [];
            foreach ($leaveRecords as $leave) {
                $period = CarbonPeriod::create(
                    Carbon::parse($leave->date_from),
                    Carbon::parse($leave->date_to)
                );

                foreach ($period as $day) {
                    if (!$day->isWeekend()) {
                        $dateStr = $day->format('Y-m-d');
                        $leaveMap[$dateStr][$leave->user_id] = true;
                    }
                }
            }

            // Create employee lookup map
            $employeeMap = $employees->keyBy('employee_number');
            $monthlyData = [];

            foreach ($months as $monthStart) {
                $monthLabel = $monthStart->format('M');
                $monthEnd = $monthStart->copy()->endOfMonth();

                // Get working days for this month
                $workingDays = collect();
                $date = $monthStart->copy();
                while ($date->lte($monthEnd)) {
                    if (!$date->isWeekend()) {
                        $workingDays->push($date->format('Y-m-d'));
                    }
                    $date->addDay();
                }

                $totalAbsent = 0;
                $totalPossible = $employees->count() * $workingDays->count();

                foreach ($workingDays as $dateStr) {
                    foreach ($employeeCodes as $empCode) {
                        $employee = $employeeMap[$empCode];
                        $attendanceKey = $dateStr . '|' . $empCode;
                        
                        $hasAttendance = isset($attendanceRecords[$attendanceKey]);
                        $hasLeave = isset($leaveMap[$dateStr][$employee->user_id]);

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

            // Generate last 7 working days
            $dates = collect();
            $day = $today->copy();
            while ($dates->count() < 7) {
                if (!$day->isWeekend()) {
                    $dates->push($day->copy());
                }
                $day->subDay();
            }
            $dates = $dates->reverse();

            // Get employees with their schedules in one query
            $employeesQuery = Employee::select('employee_number', 'schedule_id')
                ->whereIn('status', ['Active', 'HBU'])
                ->whereNotNull('employee_number')
                ->whereNotNull('schedule_id');
            
            if ($location) {
                $employeesQuery->where('location', $location);
            }
            
            $employees = $employeesQuery->get();

            if ($employees->isEmpty()) {
                return response()->json([
                    'labels' => $dates->map(fn($date) => $date->format('M d'))->values(),
                    'counts' => array_fill(0, $dates->count(), 0),
                ]);
            }

            // Get all unique schedule IDs and fetch their data
            $scheduleIds = $employees->pluck('schedule_id')->unique()->toArray();
            $schedules = DB::table('schedule_datas')
                ->whereIn('schedule_id', $scheduleIds)
                ->get()
                ->keyBy('schedule_id');

            // Create employee-schedule mapping
            $employeeSchedules = [];
            foreach ($employees as $employee) {
                if (isset($schedules[$employee->schedule_id])) {
                    $schedule = $schedules[$employee->schedule_id];
                    $expectedTimeString = trim($schedule->time_in_from);
                    
                    // Normalize the time format (same logic as original code)
                    if (strlen($expectedTimeString) == 5) {
                        $expectedTimeString = $expectedTimeString . ':00';
                    }
                    
                    $employeeSchedules[$employee->employee_number] = $expectedTimeString;
                }
            }

            // Get employee numbers that have schedules
            $employeeNumbers = array_keys($employeeSchedules);

            if (empty($employeeNumbers)) {
                return response()->json([
                    'labels' => $dates->map(fn($date) => $date->format('M d'))->values(),
                    'counts' => array_fill(0, $dates->count(), 0),
                ]);
            }

            // Bulk fetch attendance records
            $startDate = $dates->first()->format('Y-m-d');
            $endDate = $dates->last()->format('Y-m-d');

            $attendanceRecords = Attendance::select('employee_code', 'time_in')
                ->whereIn('employee_code', $employeeNumbers)
                ->whereDate('time_in', '>=', $startDate)
                ->whereDate('time_in', '<=', $endDate)
                ->get()
                ->groupBy(function($record) {
                    return Carbon::parse($record->time_in)->format('Y-m-d') . '|' . $record->employee_code;
                });

            // Calculate late counts for each date
            $lateCounts = [];
            foreach ($dates as $date) {
                $dateStr = $date->format('Y-m-d');
                $lateCount = 0;

                foreach ($employeeNumbers as $empNum) {
                    $key = $dateStr . '|' . $empNum;
                    
                    if (isset($attendanceRecords[$key]) && isset($employeeSchedules[$empNum])) {
                        // Get the employee's expected time for this specific date
                        $expectedTimeString = $employeeSchedules[$empNum];
                        
                        try {
                            $expectedTime = Carbon::parse($date->format('Y-m-d') . ' ' . $expectedTimeString);
                        } catch (Exception $e) {
                            // Skip if time parsing fails
                            \Log::error("Failed to parse expected time for employee", [
                                'employee_number' => $empNum,
                                'expected_time_string' => $expectedTimeString,
                                'date' => $date->format('Y-m-d'),
                                'error' => $e->getMessage()
                            ]);
                            continue;
                        }

                        // Check if any attendance record for this employee on this date is late
                        foreach ($attendanceRecords[$key] as $attendance) {
                            $timeIn = Carbon::parse($attendance->time_in);
                            if ($timeIn->gt($expectedTime->copy()->addMinute())) {
                                    $lateCount++;
                                    break;
                                }
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
