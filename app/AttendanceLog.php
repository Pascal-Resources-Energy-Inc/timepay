<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = [
        'last_id',
        'emp_code',
        'date',
        'datetime',
        'type',
        'location',
        'ip_address',
        'image',
        'location_maps',
        'long',
        'lat',
    ];    

    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_code','employee_number');
    }
}
