<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProcessingCoeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $coe;

    public function __construct($coe)
    {
        $this->coe = $coe;
    }

    public function build()
    {
        $subject = 'COE Request Processing';
        if ($this->coe->reference_number) {
            $subject .= ' - ' . $this->coe->reference_number;
        }
        return $this->subject($subject)->view('email.processing_coe');
    }
}