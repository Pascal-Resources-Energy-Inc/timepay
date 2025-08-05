<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ApproverSetting extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'approver_settings';
    
    protected $fillable = [
        'user_id',
        'company_id', 
        'type_of_form',
        'status'
    ];

    protected $attributes = [
        'status' => 'Active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public static function getFormTypes()
    {
        return [
            'leave' => 'Leaves',
            'ot' => 'Overtime',
            'dtr' => 'Daily Time Record',
            'pd' => 'Payroll Disbursement',
            'ad' => 'Authority Deduct',
            'ne' => 'Number Enrollment',
            'coe' => 'COE Request'
        ];
    }

    public function getFormTypeNameAttribute()
    {
        $formTypes = self::getFormTypes();
        return $formTypes[$this->type_of_form] ?? $this->type_of_form;
    }
}