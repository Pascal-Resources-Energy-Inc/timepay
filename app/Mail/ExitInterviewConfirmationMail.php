<?php

namespace App\Mail;

use App\ExitInterview;
use App\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExitInterviewConfirmationMail extends Mailable {
    use Queueable, SerializesModels;

    public $exitInterview;

    public function __construct(ExitInterview $exitInterview) {
        $this->exitInterview = $exitInterview;
    }

    public function build() {
        $hrHeadEmail = Employee::with('user')
            ->where('position', 'HR Head')
            ->where('status', 'Active')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('email');
            })
            ->firstOrFail()
            ->user
            ->email;

        return $this
            ->replyTo($hrHeadEmail, 'Human Resources')
            ->subject('Exit Interview Submission | ' . $this->exitInterview->reference_no)
            ->view('email.exit_interview_confirmation');
    }
}
