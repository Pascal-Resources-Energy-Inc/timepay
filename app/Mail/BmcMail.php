<?php

namespace App\Mail;

use App\MarketingCollateralBorrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BmcMail extends Mailable {
    use Queueable, SerializesModels;

    public $borrowing;
    public $type;

    public function __construct(MarketingCollateralBorrowing $borrowing, $type) {
        $this->borrowing = $borrowing;
        $this->type = $type;
    }

    public function build() {
        $subject = [
            'submitted' => 'For Approval',
            'approved' => 'Your request has been approved.',
            'declined' => 'Your request has been denied',
        ];

        return $this->subject($this->borrowing->reference_no . ' | ' . $subject[$this->type])
            ->view('email.bmc');
    }
}
