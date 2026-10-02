<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResignationPeriodMail extends Mailable {
    use Queueable, SerializesModels;

    public $employee;

    public function __construct($employee) {
        $this->employee = $employee;
    }

    public function build() {
        return $this ->subject(
            'Employee resignation period | ' . $this->employee->employee_number
        )->view('email.resignation_period');
    }
}
