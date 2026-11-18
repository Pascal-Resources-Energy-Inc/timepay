<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BmcItemSerial extends Model
{
    const STATUS_AVAILABLE = 'Available';
    const STATUS_RETURNED = 'Returned';

    protected $table = 'bmc_accountability_item_serials';

    protected $fillable = [
        'bmc_accountability_item_id',
        'serial_number',
        'mbd_remarks',
        'status',
        'returned_at',
        'returned_by',
    ];

    protected $dates = [
        'returned_at',
    ];

    public function item()
    {
        return $this->belongsTo(
            BmcAccountabilityItem::class,
            'bmc_accountability_item_id'
        );
    }

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }
}
