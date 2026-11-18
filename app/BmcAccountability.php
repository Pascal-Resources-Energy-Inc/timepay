<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BmcAccountability extends Model
{
    protected $table = 'bmc_accountabilities';

    const STATUS_RELEASED = 'Released';
    const STATUS_FOR_RETURN_CONFIRMATION = 'For Return Confirmation';
    const STATUS_PARTIALLY_RETURNED = 'Partially Returned';
    const STATUS_CLOSED = 'Closed';

    protected $fillable = [
        'marketing_collateral_borrowing_id',
        'accountability_ref',
        'released_by',
        'released_at',
        'expected_return_at',
        'release_notes',
        'mor',
        'status',
        'borrower_signature',
        'signed_at',
        'closed_by',
        'closed_at',
        'closing_remarks',

        'payment_method',
        'proof_of_transfer',
    ];

    protected $dates = [
        'released_at',
        'expected_return_at',
        'signed_at',
        'closed_at',
    ];

    public function borrowing()
    {
        return $this->belongsTo(
            MarketingCollateralBorrowing::class,
            'marketing_collateral_borrowing_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            BmcAccountabilityItem::class,
            'bmc_accountability_id'
        );
    }

    public function extensions()
    {
        return $this->hasMany(
            BmcAccountabilityExtension::class,
            'bmc_accountability_id'
        )->orderBy('id', 'desc');
    }

    public function returnHistory()
    {
        return $this->hasMany(
            BmcAccountabilityReturn::class,
            'bmc_accountability_id'
        )
            ->whereNotNull('received_by')
            ->orderBy('returned_at', 'desc');
    }

    public function pendingReturns()
    {
        return $this->hasMany(
            BmcAccountabilityReturn::class,
            'bmc_accountability_id'
        )
            ->whereNull('received_by')
            ->orderBy('id');
    }

    public function releasedBy()
    {
        return $this->belongsTo(
            User::class,
            'released_by'
        );
    }

    public function closedBy()
    {
        return $this->belongsTo(
            User::class,
            'closed_by'
        );
    }
}
