<?php

namespace App\Mail;

use App\MarketingMaterialRelease;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketingMaterialReleaseEditedMail extends Mailable {
    use Queueable, SerializesModels;

    public $release;
    public $editorName;
    public $requestUrl;

    public function __construct(MarketingMaterialRelease $release, $editor) {
        $this->release = $release;
        $this->editorName = $editor->name ?: $editor->email;
        $this->requestUrl = route('marketing-material-releases.show', $release->id);
    }

    public function build() {
        return $this
            ->subject('Marketing Material Release Updated - ' . $this->release->reference_no)
            ->view('email.marketing_material_release_edited');
    }
}
