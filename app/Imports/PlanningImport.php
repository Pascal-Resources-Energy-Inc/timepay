<?php

namespace App\Imports;

use App\Planning;
use App\Employee;
use App\ScheduleData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PlanningImport implements ToModel, WithHeadingRow, WithValidation
{
    private $rowCount = 0;
    
    public function model(array $row)
    {
        $user = Auth::user();
        
        if (!$user || !$user->employee) {
            throw new \Exception('User must have an employee record');
        }
        
        $employee = $user->employee;
        
        $date = $this->parseDate($row['date']);
        
        $schedules = ScheduleData::where('schedule_id', $employee->schedule_id)->get();
        $schedule = employeeSchedule($schedules, $date, $employee->schedule_id, $employee->employee_number);
        
        if ($schedule && $schedule->time_in_from && $schedule->time_out_to) {
            $timeIn = $schedule->time_in_from;
            $timeOut = $schedule->time_out_to;
        } else {
            $timeIn = '09:00';
            $timeOut = '18:00';
        }
        
        $get_approvers = new \App\Http\Controllers\EmployeeApproverController;
        $all_approvers = $get_approvers->get_approvers($user->id);
        
        $approver = null;
        if ($all_approvers && count($all_approvers) > 0) {
            $approver = $all_approvers[0]->employee_id ?? null;
        }
        
        $this->rowCount++;
        
        return new Planning([
            'date' => $date,
            'name' => $employee->id,
            'destination' => $row['destination'],
            'est_timein' => $timeIn,
            'est_timeout' => $timeOut,
            'activity' => $row['activity'],
            'remarks' => null,
            'approver' => $approver,
            'status' => 'Approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    public function rules(): array
    {
        return [
            'date' => 'required',
            'destination' => 'required|string|max:255',
            'activity' => 'required|string|max:255',
        ];
    }
    
    public function customValidationMessages()
    {
        return [
            'date.required' => 'Date is required in row :row',
            'destination.required' => 'Destination is required in row :row',
            'activity.required' => 'Activity is required in row :row',
        ];
    }
    
    private function parseDate($date)
    {
        // Handle different date formats
        if (is_numeric($date)) {
            // Excel serial date
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))->format('Y-m-d');
        }
        
        // Try to parse as string date
        return Carbon::parse($date)->format('Y-m-d');
    }
    
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}