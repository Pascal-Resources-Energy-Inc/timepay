<?php

namespace App\Console\Commands;

use App\ApproverSetting;
use App\BmcAccountability;
use App\Mail\BmcExpectedReturnReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBmcExpectedReturnReminders extends Command
{
    protected $signature = 'bmc:send-return-due-reminders';

    protected $description =
        'Notify BMC requestors and approvers about items due for return today';

    public function handle()
    {
        $accountabilities = BmcAccountability::with([
            'borrowing',
            'items.serials',
        ])
            ->whereDate('expected_return_at', today())
            ->whereIn('status', [
                BmcAccountability::STATUS_RELEASED,
                BmcAccountability::STATUS_PARTIALLY_RETURNED,
            ])
            ->get()
            ->filter(function ($accountability) {
                return $accountability->items->contains(function ($item) {
                    return (int) $item->released_quantity >
                        (int) ($item->returned_quantity ?? 0);
                });
            });

        if ($accountabilities->isEmpty()) {
            $this->info('No BMC accountabilities are due for return today.');

            return 0;
        }

        $approvers = ApproverSetting::with('user')
            ->where('type_of_form', 'bmc')
            ->where('status', 'Active')
            ->get()
            ->filter(function ($approver) {
                return $approver->user && $approver->user->email;
            })
            ->unique(function ($approver) {
                return strtolower($approver->user->email);
            });

        $sent = 0;
        $failed = 0;

        foreach ($accountabilities as $accountability) {
            $borrowing = $accountability->borrowing;

            if (!$borrowing) {
                $failed++;
                Log::warning(
                    'BMC expected-return reminder skipped because the borrowing record is missing.',
                    ['accountability_id' => $accountability->id]
                );

                continue;
            }

            if ($borrowing && $borrowing->email) {
                try {
                    Mail::to($borrowing->email)->send(
                        new BmcExpectedReturnReminderMail(
                            $accountability,
                            BmcExpectedReturnReminderMail::RECIPIENT_REQUESTOR
                        )
                    );
                    $sent++;
                } catch (\Exception $exception) {
                    $failed++;
                    Log::error(
                        'BMC expected-return reminder failed for requestor.',
                        [
                            'accountability_id' => $accountability->id,
                            'email' => $borrowing->email,
                            'error' => $exception->getMessage(),
                        ]
                    );
                }
            }

            foreach ($approvers as $approver) {
                try {
                    Mail::to($approver->user->email)->send(
                        new BmcExpectedReturnReminderMail(
                            $accountability,
                            BmcExpectedReturnReminderMail::RECIPIENT_APPROVER
                        )
                    );
                    $sent++;
                } catch (\Exception $exception) {
                    $failed++;
                    Log::error(
                        'BMC expected-return reminder failed for approver.',
                        [
                            'accountability_id' => $accountability->id,
                            'approver_id' => $approver->user_id,
                            'email' => $approver->user->email,
                            'error' => $exception->getMessage(),
                        ]
                    );
                }
            }
        }

        $this->info(
            "Sent {$sent} BMC expected-return reminder(s) for " .
            $accountabilities->count() . ' accountability record(s).'
        );

        if ($failed > 0) {
            $this->warn("{$failed} reminder(s) failed. Check the application log.");
        }

        return $failed > 0 ? 1 : 0;
    }
}
