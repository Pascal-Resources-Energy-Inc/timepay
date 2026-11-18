<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BmcAccountabilityItem extends Model
{
    protected $table = 'bmc_accountability_items';

    protected $fillable = [
        'bmc_accountability_id',
        'item_name',
        'released_quantity',
        'serial_number',
        'condition_on_release',
        'deposit_amount',
        'returned_quantity',
        'condition_on_return',
        'returned_at',
        'return_remarks',
    ];

    protected $dates = [
        'returned_at',
    ];

    public function accountability()
    {
        return $this->belongsTo(
            BmcAccountability::class,
            'bmc_accountability_id'
        );
    }

    public function returnHistory()
    {
        return $this->hasMany(
            BmcAccountabilityReturn::class,
            'bmc_accountability_item_id'
        )
            ->whereNotNull('received_by')
            ->orderBy('returned_at', 'desc');
    }

    public function serials()
    {
        return $this->hasMany(
            BmcItemSerial::class,
            'bmc_accountability_item_id'
        )->orderBy('id');
    }
}
