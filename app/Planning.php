<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    protected $table = 'plannings';
    
    protected $fillable = [
        'date',
        'name',
        'destination',
        'est_timein',
        'est_timeout',
        'activity',
        'remarks',
        'approver',
        'status',
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the employee associated with this planning
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'name', 'id');
    }
    
    /**
     * Get the approver information
     */
    public function approver_info()
    {
        return $this->belongsTo(Employee::class, 'approver', 'id');
    }
}