<?php

namespace App\Mail;

use App\LayoutDesign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LayoutDesignMail extends Mailable {
    use Queueable, SerializesModels;

    public $ldr;
    public $type;
    public $hasEmailAttachments = false;
    public $hasPortalOnlyAttachments = false;
    public $emailAttachmentCount = 0;
    public $emailAttachmentLimitMb = 15;
    public $outputLinks = [];

    public function __construct(LayoutDesign $ldr, $type) {
        $this->ldr = $ldr;
        $this->type = $type;
    }

    public function build() {
        $ref = $this->ldr->reference_no;
        $requestorName = trim($this->ldr->requestor_first_name . ' ' . $this->ldr->requestor_last_name);

        $subjects = [
            'submitted' => $ref . ' | ' . $requestorName,
            'approved' => 'Your layout design request has been approved | ' . $ref,
            'processed' => 'Your layout design request has been processed | ' . $ref,
            'declined'  => 'Your request has been denied | ' . $ref 
        ];

        $mail = $this->subject($subjects[$this->type] ?? 'Layout Design Request | ' . $ref);

        if ($this->type === 'processed') {
            $this->ldr->loadMissing('outputAttachments');
            $emailAttachmentLimit = $this->emailAttachmentLimitMb * 1024 * 1024;
            $currentAttachmentSize = 0;

            foreach ($this->ldr->outputAttachments as $attachment) {
                if ($attachment->isExternalLink()) {
                    $this->outputLinks[] = [
                        'name' => $attachment->display_name,
                        'url' => $attachment->public_url,
                    ];
                    continue;
                }

                $attachmentPath = public_path(
                    ltrim($attachment->output_attachment_path, '/\\')
                );

                if (!is_file($attachmentPath)) {
                    continue;
                }

                $fileSize = (int) $attachment->file_size;
                if ($fileSize <= 0) {
                    $fileSize = (int) filesize($attachmentPath);
                }

                if (($currentAttachmentSize + $fileSize) > $emailAttachmentLimit) {
                    $this->hasPortalOnlyAttachments = true;
                    continue;
                }

                $attachmentOptions = [
                    'as' => $attachment->original_name
                        ?: basename($attachment->output_attachment_path),
                ];

                if ($attachment->mime_type) {
                    $attachmentOptions['mime'] = $attachment->mime_type;
                }

                $mail->attach($attachmentPath, $attachmentOptions);
                $currentAttachmentSize += $fileSize;
                $this->hasEmailAttachments = true;
                $this->emailAttachmentCount++;
            }
        }

        return $mail->view('email.layout_design');
    }
}
