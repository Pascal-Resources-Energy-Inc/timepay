<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalByAmount extends Model
{
    protected $table = 'approval_by_amount';
    
    protected $fillable = [
        'type_of_form',
        'higher_than',
        'less_than',
        'created_by',
        'updated_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}