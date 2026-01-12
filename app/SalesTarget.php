<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'target_amount',
        'notes',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logActivity($action, $details = null)
    {
        return TdsActivityLog::create([
            'user_id' => auth()->id(),
            'tds_id' => null,
            'action' => $action,
            'record_type' => 'sales_target',
            'record_identifier' => "TARGET-{$this->id}",
            'details' => $details,
        ]);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForMonth($query, $month)
    {
        return $query->where('month', $month);
    }
}