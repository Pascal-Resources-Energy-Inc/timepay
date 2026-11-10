<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LayoutDesignAttachment extends Model {
    protected $table = 'layout_design_attachments';

    const PEG_SAMPLE_LINK_MIME_TYPE = 'text/uri-list;type=peg-sample';
    const OUTPUT_LINK_MIME_TYPE = 'text/uri-list;type=output';

    protected $fillable = [
        'layout_design_request_id',
        'output_attachment_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function layoutDesign() {
        return $this->belongsTo(LayoutDesign::class, 'layout_design_request_id');
    }

    public function isExternalLink() {
        return in_array($this->mime_type, [
            self::PEG_SAMPLE_LINK_MIME_TYPE,
            self::OUTPUT_LINK_MIME_TYPE,
        ], true);
    }

    public function getPublicUrlAttribute() {
        if ($this->isExternalLink()) {
            return $this->output_attachment_path;
        }

        return asset(ltrim($this->output_attachment_path, '/'));
    }

    public function getDisplayNameAttribute() {
        if ($this->mime_type === self::PEG_SAMPLE_LINK_MIME_TYPE) {
            return $this->output_attachment_path;
        }

        return $this->original_name ?: basename($this->output_attachment_path);
    }
}
