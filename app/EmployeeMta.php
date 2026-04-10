<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeMta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
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
