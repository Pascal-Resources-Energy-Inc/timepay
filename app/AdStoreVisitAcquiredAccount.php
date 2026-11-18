<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdStoreVisitAcquiredAccount extends Model
{
    protected $table = 'ad_store_visit_acquired_accounts';

    public $timestamps = false;

    protected $fillable = [
        'ad_store_visit_report_id',
        'customer_name',
        'customer_type',
        'amount_purchased',
    ];

    protected $casts = [
        'amount_purchased' => 'decimal:2',
    ];

    public function report()
    {
        return $this->belongsTo(
            AdStoreVisitReport::class,
            'ad_store_visit_report_id'
        );
    }
}
