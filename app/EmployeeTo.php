<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeTo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'level',
        'status',
        'approved_by',
        'approved_date',
        'approved_head_division',
        'approved_by_head_division',
        'approval_remarks',
        'approval_remarks2',
        'totalamount_total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function created_by_info()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }  

    public function schedule()
    {
        return $this->hasMany(ScheduleData::class,'schedule_id','schedule_id');
    }  
    
    public function approver()
    {
        return $this->hasMany(EmployeeApprover::class,'user_id','user_id');
    } 

    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by','id');
    }

    public function approvedByHeadDivision()
    {
        return $this->belongsTo(User::class,'approved_by_head_division','id');
    }

    public function immediateSupervisor()
    {
        return $this->belongsTo(User::class, 'immediate_sup');
    }

    public function first_approver()
        {
            return $this->belongsTo(Employee::class, 'approved_by');
        }

        public function last_approver()
    {
        return $this->belongsTo(User::class, 'approved_by_head_division', 'id');
    }

    public function approvalRemarks()
    {
        return $this->hasMany(EmployeeToApprovalRemark::class, 'employee_to_id')
                    ->orderBy('level', 'asc');
    }

    public function latestApprovalRemark()
    {
        return $this->hasOne(EmployeeToApprovalRemark::class, 'employee_to_id')
                    ->latest('action_date');
    }

    public function approvalRemarkByLevel($level)
    {
        return $this->approvalRemarks()->where('level', $level)->first();
    }

    public function isPendingAndLevelUp()
    {
        return $this->status === 'Pending' && $this->level >= 1;
    }

    

}