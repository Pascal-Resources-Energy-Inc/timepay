<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdStoreVisitPointPerson extends Model
{
    protected $table = 'ad_store_visit_point_persons';

    public $timestamps = false;

    protected $fillable = [
        'ad_store_visit_report_id',
        'role',
        'full_name',
        'is_present',
        'comment',
    ];

    protected $casts = [
        'is_present' => 'boolean',
    ];

    public function report()
    {
        return $this->belongsTo(
            AdStoreVisitReport::class,
            'ad_store_visit_report_id'
        );
    }
}
