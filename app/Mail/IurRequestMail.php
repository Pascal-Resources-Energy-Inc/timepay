<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IurRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $iur_reference;
    public $employee_name;
    public $request_for;
    public $work_location;
    public $type;
    public $details;
    public $iur_id;
    public $status;
    public $approval_remarks;

    // Uniform fields
    public $prod_qty;
    public $white_qty;
    public $black_qty;
    public $collar_qty;
    public $issued;
    public $issued_remarks;
    public $issued_reasons;
    public $size;
    public $notes;

    // ID fields
    public $id_request;

    public function __construct($iur, $status = 'pending', $approval_remarks = null)
    {
        $this->iur_reference = $iur->iur_reference;
        $this->employee_name = $iur->user->first_name . ' ' . $iur->user->last_name;
        $this->request_for = $iur->request_for;
        $this->work_location = $iur->work_location;
        $this->type = $iur->type;
        $this->details = $iur->details;
        $this->iur_id = $iur->id;
        $this->status = $status;
        $this->approval_remarks = $approval_remarks;

        // Uniform
        $this->prod_qty = $iur->prod_qty ?? 0;
        $this->white_qty = $iur->white_qty ?? 0;
        $this->black_qty = $iur->black_qty ?? 0;
        $this->collar_qty = $iur->collar_qty ?? 0;
        $this->issued = $iur->issued ?? null;
        $this->issued_remarks = $iur->issued_remarks ?? null;
        $this->issued_reasons = $iur->issued_reasons ?? null;
        $this->size = $iur->size ?? null;
        $this->notes = $iur->notes ?? null;

        // ID
        $this->id_request = $iur->id_request ?? null;
    }

    public function build()
    {
        $subjects = [
            'Pending'             => 'New IUR Request - ' . $this->iur_reference,
            'Processing'          => 'Your request is being processed - ' . $this->iur_reference,
            'Correction Required' => 'Action Required - ' . $this->iur_reference,
            'Declined'            => 'Request declined - ' . $this->iur_reference,
        ];

        $subject = $subjects[$this->status] ?? $subjects['Pending'];

        return $this->subject($subject)->view('email.iur_request');
    }
}
