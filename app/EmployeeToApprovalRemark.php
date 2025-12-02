<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeToApprovalRemark extends Model
{
    protected $table = 'employee_to_approval_remarks';

    protected $fillable = [
        'employee_to_id',
        'approver_id',
        'level',
        'action',
        'remarks',
        'action_date'
    ];

    protected $casts = [
        'action_date' => 'datetime'
    ];

    public function employeeTo()
    {
        return $this->belongsTo(EmployeeTo::class, 'employee_to_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}