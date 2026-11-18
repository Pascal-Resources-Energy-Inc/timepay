<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdStoreVisitAttachment extends Model
{
    protected $table = 'ad_store_visit_attachments';

    protected $fillable = [
        'ad_store_visit_report_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function report()
    {
        return $this->belongsTo(
            AdStoreVisitReport::class,
            'ad_store_visit_report_id'
        );
    }
}
