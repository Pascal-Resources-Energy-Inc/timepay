<?php

namespace App\Mail;

use App\BmcAccountabilityExtension;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BmcExtensionRequestMail extends Mailable {
    use Queueable, SerializesModels;

    public $extension;

    public function __construct(BmcAccountabilityExtension $extension) {
        $this->extension = $extension;
    }

    public function build() {
        $heading = [
            BmcAccountabilityExtension::STATUS_APPROVED => 'Extension Request Approved',
            BmcAccountabilityExtension::STATUS_DECLINED => 'Extension Request Declined',
        ][$this->extension->status] ?? 'Extension Request';

        return $this->subject($heading . ' | ' . $this->extension->accountability->accountability_ref)
            ->view('email.bmc_extension_request', ['heading' => $heading]);
    }
}
