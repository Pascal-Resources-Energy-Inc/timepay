<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IurPendingReminderMail extends Mailable {
    use Queueable, SerializesModels;

    public $iur_reference;
    public $employee_name;
    public $request_for;
    public $work_location;
    public $created_at;
    public $days_pending;

    public function __construct($iur) {
        $this->iur_reference = $iur->iur_reference;
        $this->employee_name = $iur->user->first_name . ' ' . $iur->user->last_name;
        $this->request_for = $iur->request_for;
        $this->work_location = $iur->work_location;
        $this->created_at = $iur->created_at;
        $this->days_pending = now()->diffInDays($iur->created_at);
    }

    public function build() {
        $subject = 'Reminder: IUR Request Pending';

        if ($this->iur_reference) {
            $subject .= ' - ' . $this->iur_reference;
        }

        return $this->subject($subject)->view('email.iur_reminder');
    }
}
