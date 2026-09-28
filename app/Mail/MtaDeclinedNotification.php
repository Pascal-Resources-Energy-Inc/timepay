<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\EmployeeMta;
use App\User;

class MtaDeclinedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mta;
    public $approver;

    public function __construct(EmployeeMta $mta, User $approver)
    {
        $this->mta = $mta;
        $this->approver = $approver;
    }

    public function build()
    {
        return $this->subject('MTA Request Declined')
                    ->view('email.mta_declined_notification')
                    ->with([
                        'mta' => $this->mta,
                        'approver' => $this->approver,
                    ]);
    }
}
