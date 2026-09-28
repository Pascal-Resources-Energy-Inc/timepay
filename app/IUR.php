<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class IUR extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = 'employee_iur';
    protected $fillable = [
        'user_id',
        'type', 
        'work_location',
        'request_for',
        'details',
        'issued', 
        'issued_remarks',
        'issued_reasons',
        'size',
        'notes', 
        'id_request',
        'id_picture',
        'status',
        'approved_date',
        'approval_remarks',
        'approved_by'
    ];
    
    public function user()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->hasMany(EmployeeApprover::class,'user_id','user_id');
    } 

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }  

    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by','id');
    }
}
