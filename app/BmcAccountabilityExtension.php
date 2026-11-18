<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BmcAccountabilityExtension extends Model
{
    protected $table = 'bmc_accountability_extensions';

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';

    protected $fillable = [
        'bmc_accountability_id',
        'current_return_date',
        'requested_return_date',
        'reason',
        'requested_by',
        'status',
        'action_by',
        'remarks',
    ];

    protected $casts = [
        'current_return_date' => 'date',
        'requested_return_date' => 'date',
    ];

    public function accountability()
    {
        return $this->belongsTo(BmcAccountability::class, 'bmc_accountability_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
