<?php

namespace App\Mail;

use App\MarketingMaterialRelease;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketingMaterialReleaseConfirmationMail extends Mailable {
    use Queueable, SerializesModels;

    public $release;
    public $editUrl;
    public $editExpiresAt;

    public function __construct(
        MarketingMaterialRelease $release,
        $editUrl,
        $editExpiresAt
    ) {
        $this->release = $release;
        $this->editUrl = $editUrl;
        $this->editExpiresAt = $editExpiresAt;
    }

    public function build() {
        return $this
            ->subject('Marketing Material Release Submitted - ' . $this->release->reference_no)
            ->view('email.marketing_material_release_confirmation');
    }
}
