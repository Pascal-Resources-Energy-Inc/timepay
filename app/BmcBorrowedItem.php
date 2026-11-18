<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BmcBorrowedItem extends Model {
    protected $table = 'bmc_borrowed_items';

    protected $fillable = [
        'marketing_collateral_borrowing_id',
        'item_name',
        'requested_quantity',
        'approved_quantity'
    ];

    protected $casts = [
        'marketing_collateral_borrowing_id' => 'integer',
        'requested_quantity' => 'integer',
        'approved_quantity' => 'integer'
    ];

    public function borrowing() {
        return $this->belongsTo(
            MarketingCollateralBorrowing::class,
            'marketing_collateral_borrowing_id'
        );
    }
}

