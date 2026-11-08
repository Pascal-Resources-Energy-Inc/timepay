<?php

namespace App\Mail;

use App\Employee;
use App\ExitInterview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExitInterviewSubmittedMail extends Mailable {
    use Queueable, SerializesModels;

    public $exitInterview;
    public $employee;
    public $reviewUrl;

    public function __construct(ExitInterview $exitInterview, ?Employee $employee, $reviewUrl) {
        $this->exitInterview = $exitInterview;
        $this->employee = $employee;
        $this->reviewUrl = $reviewUrl;
    }

    public function build() {
        return $this
            ->subject('Exit Interview Submitted | ' . $this->exitInterview->reference_no)
            ->view('email.exit_interview_submitted');
    }
}
