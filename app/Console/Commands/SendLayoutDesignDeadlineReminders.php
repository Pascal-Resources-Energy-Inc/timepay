<?php

namespace App\Console\Commands;

use App\ApproverSetting;
use App\LayoutDesign;
use App\Mail\LayoutDesignDeadlineReminderMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendLayoutDesignDeadlineReminders extends Command
{
    const REMINDER_DUE_SOON = 'due_soon';
    const REMINDER_DUE_TODAY = 'due_today';

    protected $signature = 'ldr:send-deadline-reminders';

    protected $description =
        'Notify active LDR approvers about requests due soon or due today';

    public function handle()
    {
        $today = Carbon::today();
        $latestDateToCheck = $today->copy()->addDays(4);

        $requests = LayoutDesign::query()
            ->where('status', LayoutDesign::STATUS_APPROVED)
            // Legacy imports keep STATUS_FROM_IMPORT and must never send reminders.
            ->where('status', '!=', LayoutDesign::STATUS_FROM_IMPORT)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', $today->toDateString())
            ->whereDate('due_date', '<=', $latestDateToCheck->toDateString())
            ->orderBy('due_date')
            ->get();

        $dueSoon = collect();
        $dueToday = collect();

        foreach ($requests as $layoutDesign) {
            $reminderType = $this->reminderTypeFor($layoutDesign, $today);

            if ($reminderType === self::REMINDER_DUE_SOON) {
                $dueSoon->push($layoutDesign);
            } elseif ($reminderType === self::REMINDER_DUE_TODAY) {
                $dueToday->push($layoutDesign);
            }
        }

        if ($dueSoon->isEmpty() && $dueToday->isEmpty()) {
            $this->info('No Layout Design deadlines require a reminder today.');
            return 0;
        }

        $approvers = ApproverSetting::with('user')
            ->where('type_of_form', 'ldr')
            ->where('status', 'Active')
            ->get()
            ->filter(function ($approver) {
                return $approver->user && $approver->user->email;
            })
            ->unique(function ($approver) {
                return strtolower($approver->user->email);
            });

        if ($approvers->isEmpty()) {
            Log::warning('LDR deadline reminders were not sent because no active approver email was found.');
            $this->warn('No active LDR approver email was found.');
            return 0;
        }

        $sent = 0;
        $failed = 0;

        foreach ($approvers as $approver) {
            try {
                Mail::to($approver->user->email)->send(
                    new LayoutDesignDeadlineReminderMail($dueSoon, $dueToday)
                );
                $sent++;
            } catch (\Exception $exception) {
                $failed++;
                Log::error('LDR deadline reminder failed.', [
                    'approver_id' => $approver->user_id,
                    'email' => $approver->user->email,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        $requestCount = $dueSoon->count() + $dueToday->count();
        $this->info(
            "Sent {$sent} LDR deadline reminder email(s) covering " .
            "{$requestCount} request(s)."
        );

        if ($failed > 0) {
            $this->warn("{$failed} reminder email(s) failed. Check the application log.");
        }

        return $failed > 0 ? 1 : 0;
    }

    public function reminderTypeFor(LayoutDesign $layoutDesign, Carbon $today)
    {
        if (
            $layoutDesign->status !== LayoutDesign::STATUS_APPROVED
            || $layoutDesign->status === LayoutDesign::STATUS_FROM_IMPORT
            || !$layoutDesign->due_date
        ) {
            return null;
        }

        $today = $today->copy()->startOfDay();
        $dueDate = Carbon::parse($layoutDesign->due_date)->startOfDay();

        if ($dueDate->lt($today)) {
            return null;
        }

        if ($dueDate->toDateString() === $today->toDateString()) {
            return self::REMINDER_DUE_TODAY;
        }

        $previousWorkingDay = $dueDate->copy()->subDay();

        while ($previousWorkingDay->isWeekend()) {
            $previousWorkingDay->subDay();
        }

        return $previousWorkingDay->toDateString() === $today->toDateString()
            ? self::REMINDER_DUE_SOON
            : null;
    }
}
