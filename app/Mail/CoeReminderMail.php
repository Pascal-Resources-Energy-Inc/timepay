<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoeReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $coe_id;
    public $coe_reference;
    public $name;
    public $purpose;
    public $reason_for_request;
    public $created_at;
    public $days_pending;
    public $due_date;

    public function __construct($data)
    {
        $this->coe_id = $data['coe_id'];
        $this->coe_reference = $data['coe_reference'];
        $this->name = $data['name'];
        $this->purpose = $data['purpose'];
        $this->reason_for_request = $data['reason_for_request'];
        $this->created_at = $data['created_at'];
        $this->days_pending = $data['days_pending'];
        $this->due_date = $data['due_date'];

    }

    public function build()
    {
        $subject = 'Reminder: COE Request Pending';
        if ($this->coe_reference) {
            $subject .= ' - ' . $this->coe_reference;
        }
        return $this->subject($subject)->view('email.coe_reminder');
    }
}
