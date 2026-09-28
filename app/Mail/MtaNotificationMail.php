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

    // public function build()
    // {
    //     return $this->subject('New MTA Request')
    //                 ->view('email.mta_notification');
    // }

    public function build()
    {
        $mail = $this->subject('New MTA Request')
            ->view('email.mta_notification')
            ->with([
                'mta' => $this->mta,
                'approver' => $this->approver
            ]);

        // ✅ FIXED PATH
        if (!empty($this->mta->attachment)) {

            $filePath = public_path($this->mta->attachment);

            if (file_exists($filePath)) {
                $mail->attach($filePath);
            }
        }

        return $mail;
    }
}
