<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeMta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'status',
        'approved_date',
        'approval_remarks',
        'approved_by',
        'payment_status',
        'processing_date',
        'payment_remarks',
        'processing_by',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }     
    
    public function approver()
    {
        return $this->hasMany(EmployeeApprover::class,'user_id','user_id');
    } 
    
    public function approverMta()
    {
        return $this->belongsTo(ApproverSetting::class,'work_location','work_location')
                ->where('type_of_form', 'mta')
                ->where('status', 'Active');
    } 
}
