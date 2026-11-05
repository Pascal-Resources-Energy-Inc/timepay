<?php

namespace App\Console\Commands;

use App\IUR;
use App\ApproverSetting;
use App\Mail\IurPendingReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class IurPendingReminder extends Command {
    protected $signature = 'iur:remind-pending';
    protected $description = 'Notify approvers of IUR requests pending for 3+ days';

    public function handle() {
        $pending = IUR::where('status', 'Pending')
            ->whereNull('reminder_sent_at')
            ->where('created_at', '<=', now())
            ->with('user')
            ->get();

        if ($pending->isEmpty()) {
            $this->info('No pending IUR requests older than 3 days.');
            return;
        }

        /* get all the approvers */
        $approvers = ApproverSetting::with('user')
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->get();

        if ($approvers->isEmpty()) {
            $this->info('No active approvers found for IUR Requests.');
            return;
        }

        $sent = 0;
        foreach ($pending as $iur) {
            foreach($approvers as $approver) {
                if ($approver->user && $approver->user->email) {
                    Mail::to($approver->user->email)
                        ->send(new IurPendingReminderMail($iur));

                    $sent++;
                }
            }

            $iur->update(['reminder_sent_at' => now()]);
        }

        $this->info("Sent {$sent} reminder(s) for {$pending->count()} pending IUR(s).");
    }
}
