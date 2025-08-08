<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use App\Attendance;
use App\DailySchedule;
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
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // Cache current user data to avoid repeated database calls
        $currentUser = auth()->user();
        $currentEmployee = $currentUser->employee;
        
        $documents = Document::get();
        $schedules = [];
        $attendance_controller = new AttendanceController;
        $current_day = date('d');
        $employee_birthday_celebrants = Employee::whereMonth('birth_date', date('m'))
        ->whereHas('company')
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
        ->whereHas('company')
          ->whereYear('original_date_hired','!=',date('Y'))
          ->whereMonth('original_date_hired', date('m'))
          ->get();

        $probationary_employee = Employee::with('department', 'company', 'user_info', 'classification_info')
            ->whereHas('company') 
            ->where('classification', "1")
            ->where(function($query) {
                $query->where('status', 'Active')
                      ->orWhere('status', 'HBU');
            })
            ->orderBy('original_date_hired')
            ->get();
        
        $emp = Employee::where('user_id', Auth::id())->first();

        $classifications = Classification::get();
        $leaveTypes = Leave::all();
        
        // User-specific data (only if user has employee record)
        $totalUsedLeaveDays = 0;
        $lateRecords = collect();
        $absentDates = [];
        $latePerDay = [];
        
        if ($currentEmployee && $currentEmployee->employee_number) {
            // Optimized used leaves calculation
            $usedLeaves = EmployeeLeave::select('date_from', 'date_to', 'halfday')
                ->where('user_id', $currentUser->id)
                ->where('status', 'Approved')
                ->whereDate('date_to', '<=', Carbon::today())
                ->get();
            
            $totalUsedLeaveDays = $usedLeaves->reduce(function ($carry, $leave) {
                if ($leave->halfday) return $carry + 0.5;
                return $carry + Carbon::parse($leave->date_from)->diffInDays(Carbon::parse($leave->date_to)) + 1;
            }, 0);
            
            // Optimized late records calculation
            $scheduleId = $currentEmployee->schedule_id;
            $schedule_ko = employeeSchedule($schedules,date('Y-m-d'),$scheduleId,auth()->user()->employee->employee_number);
            
            if ($schedule_ko) {
                $lateRecords = $this->calculateLateRecords($currentEmployee->employee_number, $schedule_ko->time_in_from);
                $absentDates = $this->calculateAbsentDates($currentEmployee->employee_number, $currentUser->id);
                $latePerDay = $this->calculateLatePerDay($currentEmployee->employee_number, $schedule_ko->time_in_from, $currentUser->id);
            }
        }
        
        // Leave plan data (only if user has employee record)
        $leave_plans_per_month = collect();
        $leave_plan_array = [];
        
        if ($currentEmployee) {
            $leave_plans_per_month = LeavePlan::select('id', 'reason', 'date_from', 'date_to')
                ->where('department_id', $currentEmployee->department_id)
                ->whereYear('date_from', date('Y'))
                ->whereMonth('date_to', date('m'))
                ->get();
            
            $leave_plans = LeavePlan::select('id', 'reason', 'date_from', 'date_to')
                ->where('department_id', $currentEmployee->department_id)
                ->get();
            
            foreach($leave_plans as $leave_plan) {
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
        }
        
        // Admin statistics (optimized with single query)
        $adminStats = $this->getOptimizedAdminStats();
        
        return view('dashboards.home', array_merge([
            'header' => '',
            'emp' => $emp,
            'date_ranges' => $date_ranges,
            'handbook' => $handbook,
            'attendance_now' => $attendance_now,
            'attendances' => $attendances,
            'schedules' => $schedules,
            'announcements' => $announcements,
            'attendance_employees' => $attendance_employees,
            'holidays' => $holidays,
            'employee_birthday_celebrants' => $employee_birthday_celebrants,
            'employees_new_hire' => $employees_new_hire,
            'employee_anniversaries' => $employee_anniversaries,
            'probationary_employee' => $probationary_employee,
            'classifications' => $classifications,
            'leaveTypes' => $leaveTypes,
            'documents' => $documents,
            'usedLeaves' => $usedLeaves ?? collect(),
            'totalUsedLeaveDays' => $totalUsedLeaveDays,
            'lateRecords' => $lateRecords,
            'absentDates' => $absentDates,
            'latePerDay' => $latePerDay,
            'leave_plans_per_month' => $leave_plans_per_month,
            'leave_plan_array' => $leave_plan_array,
        ], $adminStats));
    }
    
    /**
     * Optimized admin statistics calculation
     */
    private function getOptimizedAdminStats()
    {
        $today = Carbon::today()->toDateString();
        $tenAM = Carbon::today()->setTime(10, 0);
        
        // Single query to get all required statistics
        $stats = DB::select("
            SELECT 
                COUNT(CASE WHEN e.status IN ('Active', 'HBU') THEN 1 END) as total_employees,
                COUNT(CASE WHEN a.employee_code IS NOT NULL THEN 1 END) as present_count,
                COUNT(CASE WHEN e.status IN ('Active', 'HBU') 
                        AND a.employee_code IS NULL 
                        AND NOW() > CONCAT(CURDATE(), ' 10:00:00') 
                        AND el.user_id IS NULL 
                        THEN 1 END) as absent_count,
                COUNT(CASE WHEN la.employee_code IS NOT NULL THEN 1 END) as late_count
            FROM employees e
            LEFT JOIN (
                SELECT DISTINCT employee_code 
                FROM attendances 
                WHERE DATE(time_in) = ?
            ) a ON e.employee_number = a.employee_code
            LEFT JOIN (
                SELECT DISTINCT u.id as user_id
                FROM employee_leaves el
                JOIN users u ON el.user_id = u.id
                WHERE el.status = 'Approved'
                    AND DATE(el.date_from) <= ?
                    AND DATE(el.date_to) >= ?
            ) el ON e.user_id = el.user_id
            LEFT JOIN (
                SELECT DISTINCT e2.employee_number as employee_code
                FROM employees e2
                JOIN schedule_datas sd ON e2.schedule_id = sd.schedule_id
                JOIN attendances a2 ON e2.employee_number = a2.employee_code
                WHERE e2.status IN ('Active', 'HBU')
                    AND DATE(a2.time_in) = ?
                    AND TIME(a2.time_in) > TIME(
                        CASE 
                            WHEN LENGTH(TRIM(sd.time_in_from)) = 5 
                            THEN ADDTIME(CONCAT(TRIM(sd.time_in_from), ':00'), '00:01:00')
                            ELSE ADDTIME(TRIM(sd.time_in_from), '00:01:00') 
                        END
                    )
            ) la ON e.employee_number = la.employee_code
            WHERE e.status IN ('Active', 'HBU')
        ", [$today, $today, $today, $today]);
        
        $result = $stats[0];
        
        $locations = Employee::select('location')
            ->whereIn('status', ['Active', 'HBU'])
            ->whereNotNull('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');
        
        return [
            'total_employees' => $result->total_employees,
            'present_today_count' => $result->present_count,
            'absent_today_count' => $result->absent_count,
            'late_comers_count' => $result->late_count,
            'locations' => $locations,
        ];
    }
    
    /**
     * Optimized late records calculation
     */
   private function calculateLateRecords($employeeNumber, $expectedTimeIn)
        {
            return DB::select("
                SELECT 
                    DATE(time_in) as date,
                    DATE_FORMAT(MIN(time_in), '%h:%i %p') as time,
                    FLOOR((TIME_TO_SEC(MIN(time_in)) - TIME_TO_SEC(?)) / 60) as late_minutes
                FROM attendances 
                WHERE employee_code = ? 
                    AND MONTH(time_in) = MONTH(NOW()) 
                    AND YEAR(time_in) = YEAR(NOW())
                    AND TIME(time_in) > ADDTIME(TIME(?), '00:01:00')  -- More than 1 min late
                    AND time_in IS NOT NULL
                GROUP BY DATE(time_in)
                ORDER BY DATE(time_in) ASC
            ", [$expectedTimeIn, $employeeNumber, $expectedTimeIn]);
        }
    
    /**
     * Optimized absent dates calculation
     */
    private function calculateAbsentDates($employeeNumber, $userId)
        {
            $start = Carbon::now()->startOfMonth()->toDateString();
            $today = Carbon::now()->startOfDay()->toDateString();

            $workingDays = [];
            $period = CarbonPeriod::create($start, $today);

            foreach ($period as $date) {
                if (!$date->isWeekend()) {
                    $workingDays[] = $date->toDateString();
                }
            }

            if (empty($workingDays)) return [];

            $holidays = Holiday::selectRaw('DATE(holiday_date) as holiday_date_only')
                ->whereIn(DB::raw('DATE(holiday_date)'), $workingDays)
                ->get()
                ->pluck('holiday_date_only')
                ->toArray();

            $attendanceDays = Attendance::selectRaw('DATE(time_in) as attendance_date')
                ->where('employee_code', $employeeNumber)
                ->whereIn(DB::raw('DATE(time_in)'), $workingDays)
                ->get()
                ->pluck('attendance_date')
                ->toArray();

            $leaveDays = EmployeeLeave::where('user_id', $userId)
                ->where('status', 'Approved')
                ->where(function($query) use ($start, $today) {
                    $query->whereBetween('date_from', [$start, $today])
                        ->orWhereBetween('date_to', [$start, $today])
                        ->orWhere(function($q) use ($start, $today) {
                            $q->where('date_from', '<=', $start)
                                ->where('date_to', '>=', $today);
                        });
                })
                ->get()
                ->flatMap(function($leave) {
                    return CarbonPeriod::create($leave->date_from, $leave->date_to)->toArray();
                })
                ->map(function($date) {
                    return $date->toDateString();
                })
                ->toArray();

            $presentOrExcused = array_unique(array_merge($attendanceDays, $leaveDays, $holidays));

            return collect(array_values(array_diff($workingDays, $presentOrExcused)))
                ->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d, Y'))
                ->toArray();
                
        }
        


    private function calculateLatePerDay($employeeNumber, $expectedTimeString, $userId)
    {
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

        $attendanceData = DB::table('attendances')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('MIN(time_in) as time_in'))
            ->where('employee_code', $employeeNumber)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('time_in', 'date');

        $holidays = Holiday::whereBetween(DB::raw('DATE(holiday_date)'), [$startDate->toDateString(), $endDate->toDateString()])
            ->pluck('holiday_date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        $leaveData = EmployeeLeave::where('user_id', $userId)
            ->where('status', 'Approved')
            ->where('date_from', '<=', $endDate->toDateString())
            ->where('date_to', '>=', $startDate->toDateString())
            ->get();

        $hasSaturdayAttendance = DB::table('attendances')
            ->where('employee_code', $employeeNumber)
            ->whereRaw('DAYOFWEEK(created_at) = 7') // Saturday = 7
            ->exists();

        $latePerDay = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $dateStr = $current->toDateString();
            $dayOfWeek = $current->dayOfWeek; // 0=Sunday, 6=Saturday

            $status = 'Present';
            $lateMinutes = 0;
            $actualTimeIn = '';
            $isWorkingDay = true;

            // Sunday: always show label, never show data
            if ($dayOfWeek === 0) {
                $status = 'Skip';
                $isWorkingDay = false;
            }

            // Saturday: show label; include data only if they’ve worked Saturdays
            if ($dayOfWeek === 6 && !$hasSaturdayAttendance) {
                $status = 'Skip';
                $isWorkingDay = false;
            }

            if ($isWorkingDay && !in_array($dateStr, $holidays)) {
                if (isset($attendanceData[$dateStr])) {
                    $timeIn = Carbon::parse($attendanceData[$dateStr]);
                    $expectedTime = Carbon::parse($dateStr . ' ' . $expectedTimeString);

                    if ($timeIn->gt($expectedTime)) {
                        $lateMinutes = $expectedTime->diffInMinutes($timeIn);
                    }

                    $actualTimeIn = $timeIn->format('g:i A');
                } else {
                    $hasLeave = $leaveData->first(function ($leave) use ($dateStr) {
                        return $dateStr >= $leave->date_from && $dateStr <= $leave->date_to;
                    });

                    if (!$hasLeave && $current->lt($today)) {
                        $status = 'Absent';
                        $actualTimeIn = 'Absent';
                    } elseif (!$hasLeave && $current->isSameDay($today)) {
                        $status = 'No Attendance';
                        $actualTimeIn = 'No attendance';
                    }
                }
            }

            $latePerDay[] = [
                'date' => $current->format('F j'),
                'late_minutes' => $lateMinutes,
                'status' => $status,
                'expected_time' => $isWorkingDay ? Carbon::parse($expectedTimeString)->format('g:i A') : '',
                'actual_time_in' => $actualTimeIn,
            ];

            $current->addDay();
        }

        return $latePerDay;
    }

    

    public function filterByLocation(Request $request)
    {
        $location = $request->input('location');
        $today = Carbon::today()->toDateString();

        // Use single optimized query with subqueries
        $stats = DB::select("
            SELECT 
                COUNT(CASE WHEN e.status IN ('Active', 'HBU') THEN 1 END) as total_employees,
                COUNT(CASE WHEN a.employee_code IS NOT NULL THEN 1 END) as present_count,
                COUNT(CASE WHEN e.status IN ('Active', 'HBU') 
                        AND a.employee_code IS NULL 
                        AND NOW() > CONCAT(CURDATE(), ' 10:00:00') 
                        AND el.user_id IS NULL 
                        THEN 1 END) as absent_count,
                COUNT(CASE WHEN la.employee_code IS NOT NULL THEN 1 END) as late_count
            FROM employees e
            LEFT JOIN (
                SELECT DISTINCT employee_code 
                FROM attendances 
                WHERE DATE(time_in) = ?
            ) a ON e.employee_number = a.employee_code
            LEFT JOIN (
                SELECT DISTINCT u.id as user_id
                FROM employee_leaves el
                JOIN users u ON el.user_id = u.id
                JOIN employees emp ON u.id = emp.user_id
                WHERE el.status = 'Approved'
                    AND DATE(el.date_from) <= ?
                    AND DATE(el.date_to) >= ?
            ) el ON e.user_id = el.user_id
            LEFT JOIN (
                SELECT DISTINCT e2.employee_number as employee_code
                FROM employees e2
                JOIN schedule_datas sd ON e2.schedule_id = sd.schedule_id
                JOIN attendances a2 ON e2.employee_number = a2.employee_code
                WHERE e2.status IN ('Active', 'HBU')
                    AND DATE(a2.time_in) = ?
                    AND TIME(a2.time_in) > TIME(
                        CASE 
                            WHEN LENGTH(TRIM(sd.time_in_from)) = 5 
                            THEN ADDTIME(CONCAT(TRIM(sd.time_in_from), ':00'), '00:01:00')
                            ELSE ADDTIME(TRIM(sd.time_in_from), '00:01:00') 
                        END
                    )" . ($location ? " AND e2.location = ?" : "") . "
            ) la ON e.employee_number = la.employee_code
            WHERE e.status IN ('Active', 'HBU')" . ($location ? " AND e.location = ?" : ""),
            array_filter([
                $today, // for present count subquery
                $today, // for leave subquery date_from
                $today, // for leave subquery date_to  
                $today, // for late count subquery
                $location, // for late count location filter (if exists)
                $location  // for main query location filter (if exists)
            ])
        );

        $result = $stats[0];

        return response()->json([
            'total_employees' => $result->total_employees,
            'present_today_count' => $result->present_count,
            'absent_today_count' => $result->absent_count,
            'late_comers_count' => $result->late_count,
        ]);
    }
    
    public function getEmployees(Request $request)
    {
        try {
            $location = $request->input('location');
            
            // Start with basic query
            $query = Employee::whereIn('status', ['Active', 'HBU']);
            
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
                $query = Employee::where('company_id',"!=",2)->whereIn('status', ['Active', 'HBU']);
                
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
                    ->where('company_id',"!=",2)
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

        // 🔄 Priority: Check if DailySchedule exists for this employee
        $dailySchedule = DailySchedule::where('employee_code', $employee->employee_number)
            ->where('log_date', $today)
            ->orderBy('id', 'DESC')
            ->first();

        // 🕒 Get expected_time from DailySchedule if available, else use schedule_datas
        if ($dailySchedule && $dailySchedule->time_in_from) {
            $expectedTimeStr = $dailySchedule->time_in_from;
        } else {
            $expectedTimeStr = $employee->expected_time;
        }

        // ⏱ Normalize expected time to full time format
        $expectedTimeFormattedRaw = strlen(trim($expectedTimeStr)) === 5 
            ? $expectedTimeStr . ':00'
            : $expectedTimeStr;

        $expectedTime = \Carbon\Carbon::parse($today . ' ' . $expectedTimeFormattedRaw);
        $actualTime = \Carbon\Carbon::parse($employee->actual_time_in);

        // 🕓 Late calculation
        $lateMinutes = $actualTime->diffInMinutes($expectedTime);

        $expectedTimeFormatted = $expectedTime->format('h:i A');
        $actualTimeFormatted = $actualTime->format('h:i A');

        $lateDuration = $lateMinutes >= 60
            ? floor($lateMinutes / 60) . 'h ' . ($lateMinutes % 60) . 'm late'
            : $lateMinutes . 'm late';

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
                    'employees' => collect(),
                    'total' => 0,
                    'error' => $e
                ], 500);
            }
        }

    public function absenteesPie(Request $request)
    {
        $location = $request->input('location');
        $today = Carbon::today();

        $dates = collect();
        $day = $today->copy();
        while ($dates->count() < 7) {
            if (!$day->isWeekend()) {
                $dates->push($day->format('Y-m-d'));
            }
            $day->subDay();
        }
        $dates = $dates->reverse()->values();

        $employeesQuery = Employee::select('user_id', 'employee_number')
            ->whereIn('status', ['Active', 'HBU'])
            ->whereNotNull('employee_number');
        
        if ($location) {
            $employeesQuery->where('location', $location);
        }
        
        $employees = $employeesQuery->get();
        
        if ($employees->isEmpty()) {
            return response()->json([
                'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('M d')),
                'counts' => array_fill(0, 7, 0),
            ]);
        }

        $employeeCodes = $employees->pluck('employee_number')->toArray();
        $userIds = $employees->pluck('user_id')->toArray();
        $totalEmployees = count($employeeCodes);

        $attendanceData = DB::table('attendances')
            ->select(DB::raw('DATE(time_in) as date'), 'employee_code')
            ->whereIn('employee_code', $employeeCodes)
            ->whereDate('time_in', '>=', $dates->first())
            ->whereDate('time_in', '<=', $dates->last())
            ->get()
            ->groupBy('date')
            ->map(function ($records) {
                return $records->pluck('employee_code')->unique()->toArray();
            });

        $leaveData = DB::table('employee_leaves')
            ->select('user_id', 'date_from', 'date_to')
            ->whereIn('user_id', $userIds)
            ->where('status', 'Approved')
            ->where('date_from', '<=', $dates->last())
            ->where('date_to', '>=', $dates->first())
            ->get();

        $leaveCoverage = [];
        foreach ($dates as $date) {
            $leaveCoverage[$date] = $leaveData->filter(function ($leave) use ($date) {
                return $date >= $leave->date_from && $date <= $leave->date_to;
            })->pluck('user_id')->toArray();
        }

        $userToEmployeeMap = $employees->pluck('employee_number', 'user_id')->toArray();

        $absentCounts = [];
        foreach ($dates as $date) {
            $presentEmployees = $attendanceData->get($date, []);
            $usersOnLeave = $leaveCoverage[$date];
            $employeesOnLeave = collect($usersOnLeave)->map(fn($userId) => $userToEmployeeMap[$userId] ?? null)->filter()->toArray();
            
            $presentOrOnLeave = array_unique(array_merge($presentEmployees, $employeesOnLeave));
            $absentCount = $totalEmployees - count($presentOrOnLeave);
            
            $absentCounts[] = max(0, $absentCount);
        }

        return response()->json([
            'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('M d')),
            'counts' => $absentCounts,
        ]);
    }

    public function absenteesMonthlyPie(Request $request)
    {
        $location = $request->input('location');
        $today = Carbon::today();
        
        $lastMonth = $today->copy()->subMonth();
        $startOfLastMonth = $lastMonth->copy()->startOfMonth();
        $endOfLastMonth = $lastMonth->copy()->endOfMonth();
        
        $workingDays = collect();
        $currentDay = $startOfLastMonth->copy();
        
        while ($currentDay->lte($endOfLastMonth)) {
            if (!$currentDay->isWeekend()) {
                $workingDays->push($currentDay->format('Y-m-d'));
            }
            $currentDay->addDay();
        }
        
        if ($workingDays->isEmpty()) {
            return response()->json([
                'labels' => [$lastMonth->format('M Y')],
                'percentages' => [0],
            ]);
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
                'labels' => [$lastMonth->format('M Y')],
                'percentages' => [0],
            ]);
        }

        $employeeCodes = $employees->pluck('employee_number')->toArray();
        $userIds = $employees->pluck('user_id')->toArray();
        $totalEmployees = count($employeeCodes);
        $totalWorkingDays = $workingDays->count();
        
        $attendanceData = DB::table('attendances')
            ->select(DB::raw('DATE(time_in) as date'), 'employee_code')
            ->whereIn('employee_code', $employeeCodes)
            ->whereDate('time_in', '>=', $startOfLastMonth)
            ->whereDate('time_in', '<=', $endOfLastMonth)
            ->get()
            ->groupBy('date')
            ->map(function ($records) {
                return $records->pluck('employee_code')->unique()->toArray();
            });

        $leaveData = DB::table('employee_leaves')
            ->select('user_id', 'date_from', 'date_to')
            ->whereIn('user_id', $userIds)
            ->where('status', 'Approved')
            ->where('date_from', '<=', $endOfLastMonth)
            ->where('date_to', '>=', $startOfLastMonth)
            ->get();

        $leaveCoverage = [];
        foreach ($workingDays as $date) {
            $leaveCoverage[$date] = $leaveData->filter(function ($leave) use ($date) {
                return $date >= $leave->date_from && $date <= $leave->date_to;
            })->pluck('user_id')->toArray();
        }

        $userToEmployeeMap = $employees->pluck('employee_number', 'user_id')->toArray();

        $totalAbsentDays = 0;
        $totalPossibleDays = $totalEmployees * $totalWorkingDays;
        
        foreach ($workingDays as $date) {
            $presentEmployees = $attendanceData->get($date, []);
            $usersOnLeave = $leaveCoverage[$date];
            $employeesOnLeave = collect($usersOnLeave)
                ->map(fn($userId) => $userToEmployeeMap[$userId] ?? null)
                ->filter()
                ->toArray();
            
            $presentOrOnLeave = array_unique(array_merge($presentEmployees, $employeesOnLeave));
            $absentCount = $totalEmployees - count($presentOrOnLeave);
            
            $totalAbsentDays += max(0, $absentCount);
        }
        
        $absenteePercentage = $totalPossibleDays > 0 ? round(($totalAbsentDays / $totalPossibleDays) * 100, 1) : 0;
        $presentPercentage = 100 - $absenteePercentage;
        
        return response()->json([
            'labels' => ['Present', 'Absent'],
            'percentages' => [$presentPercentage, $absenteePercentage],
        ]);
    }

    public function latePie(Request $request)
    {
        $location = $request->input('location');
        $today = Carbon::today();

        $dates = collect();
        $day = $today->copy();
        while ($dates->count() < 7) {
            if (!$day->isWeekend()) {
                $dates->push($day->format('Y-m-d'));
            }
            $day->subDay();
        }
        $dates = $dates->reverse()->values();

        $employeesQuery = Employee::select('user_id', 'employee_number', 'schedule_id')
            ->whereIn('status', ['Active', 'HBU'])
            ->whereNotNull('employee_number')
            ->whereNotNull('schedule_id');
        
        if ($location) {
            $employeesQuery->where('location', $location);
        }
        
        $employees = $employeesQuery->get();
        
        if ($employees->isEmpty()) {
            return response()->json([
                'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('M d')),
                'counts' => array_fill(0, 7, 0),
            ]);
        }

        $employeeCodes = $employees->pluck('employee_number')->toArray();
        $scheduleIds = $employees->pluck('schedule_id')->unique()->toArray();

        $scheduleData = DB::table('schedule_datas')
            ->select('schedule_id', 'time_in_from')
            ->whereIn('schedule_id', $scheduleIds)
            ->get()
            ->keyBy('schedule_id');

        $employeeToScheduleMap = $employees->pluck('schedule_id', 'employee_number')->toArray();

        $attendanceData = DB::table('attendances')
            ->select(
                DB::raw('DATE(time_in) as date'), 
                'employee_code', 
                DB::raw('MIN(TIME(time_in)) as earliest_time_in')
            )
            ->whereIn('employee_code', $employeeCodes)
            ->whereDate('time_in', '>=', $dates->first())
            ->whereDate('time_in', '<=', $dates->last())
            ->groupBy(DB::raw('DATE(time_in)'), 'employee_code') 
            ->get()
            ->groupBy('date');

        $lateCounts = [];
        foreach ($dates as $date) {
            $dayAttendance = $attendanceData->get($date, collect());
            $lateCount = 0;

            foreach ($dayAttendance as $attendance) {
                $employeeCode = $attendance->employee_code;
                $timeIn = $attendance->earliest_time_in;
                
                $scheduleId = $employeeToScheduleMap[$employeeCode] ?? null;
                if (!$scheduleId) continue;
                
                $schedule = $scheduleData->get($scheduleId);
                if (!$schedule) continue;
                
                $timeInFrom = trim($schedule->time_in_from);
                if (strlen($timeInFrom) === 5) {
                    $timeInFrom .= ':00';
                }
                
                try {
                    $scheduleTime = Carbon::createFromFormat('H:i:s', $timeInFrom)->addMinute();
                    $attendanceTime = Carbon::createFromFormat('H:i:s', $timeIn);
                    
                    if ($attendanceTime->gt($scheduleTime)) {
                        $lateCount++;
                    }
                } catch (Exception $e) {
                    continue;
                }
            }
            
            $lateCounts[] = $lateCount;
        }

        return response()->json([
            'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('M d')),
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