<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExitInterviewInvitationMail extends Mailable {
    use Queueable, SerializesModels;

    public $employee;
    public $invitationUrl;
    public $internalInvitationUrl;
    public $replyToEmail;

    public function __construct($recipient, $invitationUrl, $internalInvitationUrl = null, $replyToEmail = null) {
        $this->employee = $recipient;
        $this->invitationUrl = $invitationUrl;
        $this->internalInvitationUrl = $internalInvitationUrl ?: url('/exit-interviews/create');
        $this->replyToEmail = $replyToEmail;
    }

    public function build() {
        $subject = 'Exit Interview Link';

        if (!empty($this->employee->employee_number)) {
            $subject .= ' | ' . $this->employee->employee_number;
        }

        $mail = $this
            ->subject($subject)
            ->view('email.exit_interview_invitation');

        if (!empty($this->replyToEmail)) {
            $mail->replyTo($this->replyToEmail, 'HR Head');
        }

        return $mail;
    }
}
