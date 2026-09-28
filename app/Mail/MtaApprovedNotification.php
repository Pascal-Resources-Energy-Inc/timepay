<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\EmployeeMta;
use App\User;

class MtaApprovedNotification extends Mailable
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
        return $this->subject('MTA Request Approved')
                    ->view('email.mta_approved_notification')
                    ->with([
                        'mta' => $this->mta,
                        'approver' => $this->approver,
                    ]);
    }
}
