<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeePd extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'employee_pds';

    protected $fillable = [
        'approved_date',
        'status',
        'approval_remarks',
        'approved_by',
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

    public function immediateSupervisor()
    {
        return $this->belongsTo(User::class, 'immediate_sup');
    }

    public function destination_dates()
    {
        return $this->hasMany(EmployeeToDate::class, 'employee_to_id', 'id  ');
    }

    

}