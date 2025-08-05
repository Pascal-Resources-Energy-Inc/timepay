<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\PayInstruction;

class AdStatusNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $employeeAd;
    public $approver;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct(PayInstruction $employeeAd, $approver, $status)
    {
        $this->employeeAd = $employeeAd;
        $this->approver = $approver;
        $this->status = $status;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Authority to Deduct - ' . $this->status;
        
        return $this->subject($subject)
                    ->view('email.ad_approved')
                    ->with([
                        'employeeAd' => $this->employeeAd,
                        'approver' => $this->approver,
                        'status' => $this->status,
                    ]);
    }
}