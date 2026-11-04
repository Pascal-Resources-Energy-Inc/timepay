<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketingMaterialReleaseInvitationMail extends Mailable {
    use Queueable, SerializesModels;

    public $invitationUrl;
    public $internalInvitationUrl;

    public function __construct($invitationUrl, $internalInvitationUrl = null) {
        $this->invitationUrl = $invitationUrl;
        $this->internalInvitationUrl = $internalInvitationUrl
            ?: url('/marketing-material-releases/create');
    }

    public function build() {
        return $this
            ->subject('Marketing Material Release Form Link')
            ->view('email.marketing_material_release_invitation');
    }
}
