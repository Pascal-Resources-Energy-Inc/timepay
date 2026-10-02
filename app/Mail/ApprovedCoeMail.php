<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedCoeMail extends Mailable {
    use Queueable, SerializesModels;

    public $coe;

    public function __construct($coe) {
        $this->coe = $coe;
    }

    public function build() {
        $subject = 'COE Request Approved';

        if ($this->coe->reference_number) {
            $subject .= ' - ' . $this->coe->reference_number;
        }
        $mail = $this->subject($subject)->view('email.approved_coe');

        if ($this->coe->receive_method === 'Email' 
            && $this->coe->attachment 
            && file_exists(public_path($this->coe->attachment))) {



            $mail->attach(public_path($this->coe->attachment), [
                'as'   => basename($this->coe->attachment),
                'mime' => mime_content_type(public_path($this->coe->attachment)),
            ]);
        }

        return $mail;
    }
}
