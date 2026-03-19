<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TdsAmountUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $record;
    public $filePath;

    public function __construct($record, $filePath = null)
    {
        $this->record = $record;
        $this->filePath = $filePath;
    }

    public function build()
    {
        $mail = $this->subject('TDS Amount Updated')
                     ->view('email.tds_amount_updated');

        // attach uploaded file if exists
        if ($this->filePath && file_exists($this->filePath)) {
            $mail->attach($this->filePath);
        }

        return $mail;
    }
}
