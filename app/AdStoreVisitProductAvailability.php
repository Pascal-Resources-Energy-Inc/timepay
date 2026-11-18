<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdStoreVisitProductAvailability extends Model
{
    protected $table = 'ad_store_visit_product_availabilities';

    public $timestamps = false;

    protected $fillable = [
        'ad_store_visit_report_id',
        'product_name',
        'sort_order',
        'is_inspected',
        'is_available',
        'remarks',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_inspected' => 'boolean',
        'is_available' => 'boolean',
    ];

    public function report()
    {
        return $this->belongsTo(
            AdStoreVisitReport::class,
            'ad_store_visit_report_id'
        );
    }
}
