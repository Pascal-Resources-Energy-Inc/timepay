<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeCoe extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

     protected $fillable = [
        'level',
        'status',
        'approved_by',
        'approved_date',
        'approval_remarks',
        'issuance_remarks'
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

    public function getSalutationAttribute() {
        $gender = null;
        $marital = null;

        if ($this->user && $this->user->employee) {
            $gender = strtolower(trim($this->user->employee->gender ?? ''));
            $marital = strtolower(trim($this->user->employee->marital_status ?? ''));
        } elseif ($this->gender) {
            $gender = strtolower(trim($this->gender));
            $marital = strtolower(trim($this->marital_status ?? ''));
        } elseif (!$this->user) {
            $match = \App\Employee::whereRaw(
                "CONCAT(first_name, ' ', last_name) = ?",
                [$this->first_name . ' ' . $this->last_name]
            )->first(['gender', 'marital_status']);
            if ($match) {
                $gender = strtolower(trim($match->gender ?? ''));
                $marital = strtolower(trim($match->marital_status ?? ''));
            }
        }
    
        if (empty($gender)) {
            return '';
        }

        if ($gender === 'male') {
            return 'Mr. ';
        }

        return ($marital === 'married') ? 'Mrs. ' : 'Ms. ';
    }

    public function getAttachmentUrlAttribute() {
        return $this->attachment ? asset($this->attachment) : null;
    }

    public function requiresAttachment() {
        $employee = optional($this->user)->employee;

        $isConfidEmployee = $this->user_id !== null 
            && $employee 
            && (int) $employee->company_id === 2;

        return in_array($this->receive_method, ['Email', 'Viber'], true)
            && !$isConfidEmployee;
    }

    public function trails()
    {
        return $this->hasMany(EmployeeCoeTrail::class, 'employee_coe_id')
            ->oldest('created_at');
    }

    public function recordTrail($action, $user = null, $remarks = null, $performedByName = null)
    {
        return $this->trails()->create([
            'action' => $action,
            'action_by' => $user ? $user->id : null,
            'performed_by_name' => $performedByName ?: ($user ? $user->name : 'Public requestor'),
            'remarks' => $remarks,
        ]);
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

}
