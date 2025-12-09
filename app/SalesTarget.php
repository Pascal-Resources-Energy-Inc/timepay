<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    protected $table = 'sales_target';

    protected $fillable = [
        'month',
        'target_amount',
        'notes',
    ];

    public function logActivity($action, $details = null)
    {
        return TdsActivityLog::create([
            'user_id' => auth()->id(),
            'tds_id' => $this->id,
            'action' => $action,
            'record_type' => 'sales_target',
            'record_identifier' => "TARGET-{$this->id}",
            'details' => is_array($details) ? json_encode($details) : $details,
        ]);
    }
}