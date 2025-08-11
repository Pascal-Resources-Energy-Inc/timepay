<?php

namespace App\Exports;

use App\DailySchedule;
use App\Employee;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyScheduleExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting
{
    protected $data;
    protected $scheduleTypes = [];

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function collection()
    {
        if ($this->data === null) {
            return collect([]);
        }
        
        if ($this->data->isNotEmpty() && !isset($this->data->first()->level)) {
            return $this->data->map(function ($schedule) {
                $employee = Employee::where('employee_code', $schedule->employee_code)->first();
                if ($employee) {
                    $schedule->level = $employee->level;
                    $schedule->employee_name = $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name;
                }
                return $schedule;
            });
        }
        
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Company',
            'Employee No.',
            'Employee Name',
            'Log Date',
            'Time In From',
            'Time In To',
            'Time Out From',
            'Time Out To',
            'Working Hours',
            'Standard Hours',
            'Schedule Type'
        ];
    }

    public function map($schedule): array
    {
        if ($this->data === null || $schedule === null) {
            return [];
        }

        $level = null;
        if (isset($schedule->level)) {
            $level = $schedule->level;
        } elseif (isset($schedule->employee_level)) {
            $level = $schedule->employee_level;
        } elseif (isset($schedule->classification)) {
            $level = $schedule->classification;
        }

        $scheduleType = 'Fixed Time';
        if ($level !== null && $level >= 3) {
            $scheduleType = 'Flexi Time';
        }

        $this->scheduleTypes[] = $scheduleType;

        return [
            $schedule->company ?? 'N/A',
            $schedule->employee_code ?? $schedule->employee_number ?? 'N/A',
            $schedule->employee_name ?? 'N/A',
            $schedule->log_date ? date('M d, Y - l', strtotime($schedule->log_date)) : 'N/A',
            $schedule->time_in_from && $schedule->time_in_from != "00:00:00" ? date('h:i A', strtotime($schedule->time_in_from)) : 'Rest Day',
            $schedule->time_in_to && $schedule->time_in_to != "00:00:00" ? date('h:i A', strtotime($schedule->time_in_to)) : 'Rest Day',
            $schedule->time_out_from && $schedule->time_out_from != "00:00:00" ? date('h:i A', strtotime($schedule->time_out_from)) : 'Rest Day',
            $schedule->time_out_to && $schedule->time_out_to != "00:00:00" ? date('h:i A', strtotime($schedule->time_out_to)) : 'Rest Day',
            $schedule->working_hours ?? '0.00',
            $schedule->standard_hours ?? '8.00',
            $scheduleType
        ];
    }

    public function styles(Worksheet $sheet) {
        $styles = [
            1 => [
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '0080c0'],
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'],
                ]
            ]
        ];
        foreach ($this->scheduleTypes as $index => $scheduleType) {
            $row = $index + 2;
            
            if ($scheduleType === 'Flexi Time') {
                $styles["K{$row}"] = [
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => '90EE90'],
                    ],
                    'font' => [
                        'bold' => false,
                        'color' => ['argb' => '000000'],
                    ]
                ];
            } elseif ($scheduleType === 'Fixed Time') {
                $styles["K{$row}"] = [
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD700'],
                    ],
                    'font' => [
                        'bold' => false,
                        'color' => ['argb' => '000000'],
                    ]
                ];
            }
        }

        return $styles;
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00
        ];
    }
}