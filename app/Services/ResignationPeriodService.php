<?php

namespace App\Services;

use App\Attendance;
use App\Employee;
use App\EmployeeLeave;
use App\Holiday;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ResignationPeriodService {
    public function countedDays(Employee $employee, ?Carbon $until = null) {
        return $this->calculate($employee, $until)['count'];
    }

    public function completionDate(Employee $employee,
        ?Carbon $until = null,
        $requiredDays = 30
    ) {
        $result = $this->calculate($employee, $until, $requiredDays);

        return $result['completion_date'];
    }

    public function hasCompleted(
        Employee $employee,
        $requiredDays = 30
    ) {
        return $this->completionDate(
            $employee,
            Carbon::today(),
            $requiredDays
        ) !== null;
    }

    private function calculate(
        Employee $employee,
        ?Carbon $until = null,
        $requiredDays = 30
    ) {
        if (!$employee->date_resigned) {
            return [
                'count' => 0,
                'completion_date' => null,
            ];
        }

        $start = Carbon::parse($employee->date_resigned)->startOfDay();
        $end = ($until ?: Carbon::today())->copy()->endOfDay();

        if ($start->greaterThan($end)) {
            return [
                'count' => 0,
                'completion_date' => null,
            ];
        }

        $attendanceDates = Attendance::where(
                'employee_code',
                $employee->employee_number
            )
            ->whereBetween('time_in', [
                $start->copy()->startOfDay()->toDateTimeString(),
                $end->copy()->endOfDay()->toDateTimeString(),
            ])
            ->whereNotNull('time_in')
            ->get(['time_in'])
            ->map(function ($attendance) {
                return Carbon::parse($attendance->time_in)
                    ->toDateString();
            })
            ->unique()
            ->flip()
            ->all();

        $holidayDates = Holiday::whereBetween('holiday_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->get(['holiday_date'])
            ->map(function ($holiday) {
                return Carbon::parse($holiday->holiday_date)
                    ->toDateString();
            })
            ->unique()
            ->flip()
            ->all();

        $leaveDates = [];

        $approvedLeaves = EmployeeLeave::where(
                'user_id',
                $employee->user_id
            )
            ->where('status', 'Approved')
            ->where('date_from', '<=', $end->toDateString())
            ->where('date_to', '>=', $start->toDateString())
            ->get(['date_from', 'date_to']);

        foreach ($approvedLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->date_from);
            $leaveEnd = Carbon::parse($leave->date_to);

            foreach (CarbonPeriod::create($leaveStart, $leaveEnd) as $date) {
                $leaveDates[$date->toDateString()] = true;
            }
        }

        $count = 0;
        $completionDate = null;
        $current = $start->copy();

        while ($current->lessThanOrEqualTo($end)) {
            $date = $current->toDateString();

            /*
             * Sunday is the only excluded weekend.
             * Saturday is eligible.
             */
            if (!$current->isSunday()) {
                $hasAttendance = isset($attendanceDates[$date]);
                $hasApprovedLeave = isset($leaveDates[$date]);
                $isHoliday = isset($holidayDates[$date]);

                /*
                 * A day is considered absent when none of these exist.
                 */
                $isAbsent = !$hasAttendance
                    && !$hasApprovedLeave
                    && !$isHoliday;

                if (!$isAbsent) {
                    $count++;

                    if ($count === $requiredDays) {
                        $completionDate = $current->copy();
                        break;
                    }
                }
            }

            $current->addDay();
        }

        return [
            'count' => $count,
            'completion_date' => $completionDate,
        ];
    }
}
