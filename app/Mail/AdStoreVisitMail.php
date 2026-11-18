<?php

namespace App\Mail;

use App\AdStoreVisitReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdStoreVisitMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $type;
    public $context;

    public function __construct(
        AdStoreVisitReport $report,
        $type,
        array $context = []
    ) {
        $this->report = $report;
        $this->type = $type;
        $this->context = $context;
    }

    public function build()
    {
        if ($this->type === 'approved') {
            $subject = !empty($this->context['automatic_approval'])
                ? 'Automatic Approved | ' . $this->report->reference_no
                : 'AD Approved | ' . $this->report->reference_no;
        } elseif ($this->type === 'disapproved') {
            $subject = 'AD Disapproved | ' . $this->report->reference_no;
        } else {
            $subject = 'Approval Required | ' . $this->report->reference_no;
        }

        return $this->subject($subject)
            ->view('email.ad_store_visit');
    }
}
