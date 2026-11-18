<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdStoreVisitOtherMatter extends Model
{
    protected $table = 'ad_store_visit_other_matters';

    public $timestamps = false;

    protected $fillable = [
        'ad_store_visit_report_id',
        'is_inspected',
        'matter',
        'status',
        'remarks',
    ];

    protected $casts = [
        'is_inspected' => 'boolean',
        'status' => 'boolean',
    ];

    public function report()
    {
        return $this->belongsTo(
            AdStoreVisitReport::class,
            'ad_store_visit_report_id'
        );
    }
}
