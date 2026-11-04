<?php

namespace App\Mail;

use App\Compenben;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompenbenSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $compenben;

    public function __construct(Compenben $compenben)
    {
        $this->compenben = $compenben;
    }

    public function build()
    {
        return $this
            ->subject(
                'Compensation and Benefits | '
                . $this->compenben->reference_no
                . ' | '
                . $this->compenben->issue
            )
            ->view('email.compenben_submitted');
    }
}
