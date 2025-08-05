<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class PayInstruction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    
    protected $table = 'pay_instructions';

    public $fillable = ['location', 'site_id', 'name', 'start_date','end_date', 'amount', 'frequency', 'deductible', 'remarks', 'status', 'approved_by', 'approved_date', 'created_by' ];

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

    public function approver()
    {
        return $this->hasMany(EmployeeApprover::class,'user_id','user_id');
    } 
    
    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by','id');
    }
}
