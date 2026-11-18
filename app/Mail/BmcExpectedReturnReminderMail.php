<?php

namespace App\Mail;

use App\BmcAccountability;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BmcExpectedReturnReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    const RECIPIENT_REQUESTOR = 'requestor';
    const RECIPIENT_APPROVER = 'approver';

    public $accountability;
    public $borrowing;
    public $recipientType;
    public $actionUrl;

    public function __construct(
        BmcAccountability $accountability,
        $recipientType
    ) {
        $this->accountability = $accountability;
        $this->accountability->loadMissing([
            'borrowing',
            'items.serials',
        ]);
        $this->borrowing = $this->accountability->borrowing;
        $this->recipientType =
            $recipientType === self::RECIPIENT_APPROVER
                ? self::RECIPIENT_APPROVER
                : self::RECIPIENT_REQUESTOR;
        $this->actionUrl =
            $this->recipientType === self::RECIPIENT_APPROVER
                ? url('/bmc-approval/' . $this->borrowing->id)
                : route('bmc.show', $this->borrowing->id);
    }

    public function build()
    {
        return $this
            ->subject(
                $this->accountability->accountability_ref .
                ' | Items Due for Return Today'
            )
            ->view('email.bmc_expected_return_reminder');
    }
}
