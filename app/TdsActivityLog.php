<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TdsActivityLog extends Model
{
    protected $table = 'tds_activity_logs';

    protected $fillable = [
        'user_id',
        'tds_id',
        'action',
        'record_type',
        'record_identifier',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tds()
    {
        return $this->belongsTo(Tds::class);
    }
}