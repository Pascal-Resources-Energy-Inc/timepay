<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tds extends Model
{
    use SoftDeletes;

    protected $table = 'tds';

    protected $fillable = [
        'date_of_registration',
        'user_id',
        'area',
        'customer_name',
        'contact_no',
        'business_name',
        'awarded_area',
        'business_type',
        'package_type',
        'purchase_amount',
        'lead_generator',
        'supplier_name',
        'status',
        'timeline',
        'additional_notes',
    ];

    protected $dates = ['date_of_registration', 'timeline', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'area');
    }

    public function activityLogs()
    {
        return $this->hasMany(TdsActivityLog::class, 'tds_id');
    }

    public function logActivity($action, $details = null)
    {
        return TdsActivityLog::create([
            'user_id' => auth()->id(),
            'tds_id' => $this->id,
            'action' => $action,
            'record_type' => 'tds',
            'record_identifier' => "TDS-{$this->id}",
            'details' => is_array($details) ? json_encode($details) : $details,
        ]);
    }
}