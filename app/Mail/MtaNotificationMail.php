<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MtaNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mta;
    public $approver;

    public function __construct($mta, $approver)
    {
        $this->mta = $mta;
        $this->approver = $approver;
    }

    public function build()
    {
        return $this->subject('New MTA Request')
                    ->view('email.mta_notification');
    }
}
