<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelledCoeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $subject = 'COE Request Cancelled';
        if ($this->data['coe_reference']) {
            $subject .= ' - ' . $this->data['coe_reference'];
        }
        return $this->subject($subject)->view('email.cancelled_coe');
    }
}