<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeCoeMail extends Mailable {
    use Queueable, SerializesModels;

    public $name;
    public $reason_for_request;
    public $employment_status;
    public $purpose;
    public $designation;
    public $hire_date;
    public $receive_method;
    public $viber_number;
    public $personal_number;
    public $additional_notes;
    public $resignation_date;
    public $coe_id;
    public $coe_reference;
    public $isRequestor = false;

    public function __construct($data) {
        $this->name = $data['name'];
        $this->reason_for_request = $data['reason_for_request'];
        $this->employment_status = $data['employment_status'];
        $this->purpose = $data['purpose'];
        $this->designation = $data['designation'];
        $this->hire_date = $data['hire_date'];
        $this->receive_method = $data['receive_method'];
        $this->viber_number = $data['viber_number'] ?? null;
        $this->personal_number = $data['personal_number'] ?? null;
        $this->additional_notes = $data['additional_notes'] ?? null;
        $this->resignation_date = $data['resignation_date'] ?? null;
        $this->coe_id = $data['coe_id'] ?? null;
        $this->coe_reference = $data['coe_reference'] ?? null;
    }


    public function build() {
        $subject = $this->isRequestor ? 'COE Request Received' : 'COE Request';
        $template = $this->isRequestor ? 'email.coe_requestor' : 'email.coe_request';

        if ($this->coe_reference) {
            $subject .= ' - ' . $this->coe_reference;
        }

        return $this->subject($subject)->view($template);
    }

    public function forRequestor() {
        $this->isRequestor = true;
        return $this;
    }
}
