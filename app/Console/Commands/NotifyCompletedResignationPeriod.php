<?php

namespace App\Console\Commands;

use App\Employee;
use App\Mail\ResignationPeriodMail;
use App\Services\ResignationPeriodService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyCompletedResignationPeriod extends Command {
    protected $signature = 'employees:notify-resignation-day-30';

    protected $description = 'Notify HR when an employee reaches 30 counted days after resignation';

    protected $resignationPeriodService;

    public function __construct(ResignationPeriodService $resignationPeriodService) {
        parent::__construct();

        $this->resignationPeriodService = $resignationPeriodService;
    }

    public function handle() {
        $today = Carbon::today();

        // rem and HR Head for the mean time
        $recipients = Employee::with('user')
            ->whereIn('position', [
                'HR Head',
                'Organizational Development - HR Assistant',
            ])
            ->where('status', 'Active')
            ->whereHas('user', function ($query) {
                $query->where('status', 'Active')
                    ->whereNotNull('email')
                    ->where('email', '!=', '');
            })
            ->get()
            ->pluck('user.email')
            ->filter(function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })
            ->unique()
            ->values()
            ->all();

        if (empty($recipients)) {
            Log::warning(
                'No active HR Head or Organizational Development - HR Assistant email was found.'
            );

            $this->warn('No eligible HR recipients were found.');

            return 1;
        }

        $employees = Employee::whereNotNull('date_resigned')
            ->whereDate('date_resigned', '<=', $today->toDateString())
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($employees as $employee) {
            $completionDate = $this->resignationPeriodService
                ->completionDate($employee, $today, 30);

            if (!$completionDate || !$completionDate->isSameDay($today)) {
                continue;
            }

            $cacheKey = 'resignation-period-email:'
                . $employee->id
                . ':'
                . $completionDate->toDateString();

            if (Cache::has($cacheKey)) {
                continue;
            }

            $employee->resignation_completion_date =
                $completionDate->toDateString();

            try {
                Mail::to($recipients)->send(
                    new ResignationPeriodMail($employee)
                );

                Cache::forever($cacheKey, true);
                $sent++;
            } catch (\Throwable $exception) {
                $failed++;

                Log::error(
                    'Failed to send resignation period notification for employee '
                    . $employee->employee_number
                    . ': '
                    . $exception->getMessage()
                );
            }
        }

        $this->info($sent . ' resignation period email(s) sent.');

        if ($failed > 0) {
            $this->error($failed . ' resignation period email(s) failed.');

            return 1;
        }

        return 0;
    }
}
