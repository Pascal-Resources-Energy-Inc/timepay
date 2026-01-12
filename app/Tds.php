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
        'location',
        'business_image',
        'package_type',
        'purchase_amount',
        'program_type',
        'program_area',
        'lead_generator',
        'supplier_name',
        'status',
        'timeline',
        'delivery_date',
        'document_attachment',
        'additional_notes',
        'latitude',
        'longitude',
    ];

    protected $dates = ['date_of_registration', 'timeline', 'delivery_date', 'deleted_at'];

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
            'details' => $details,
        ]);
    }

    public function getDocumentPathAttribute()
    {
        if ($this->document_attachment) {
            return storage_path('app/public/tds_documents/' . $this->document_attachment);
        }
        return null;
    }

    public function getDocumentUrlAttribute()
    {
        if ($this->document_attachment) {
            return asset('storage/tds_documents/' . $this->document_attachment);
        }
        return null;
    }
}