<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeCoeTrail extends Model
{
    const ACTION_REQUEST_SUBMITTED = 'request_submitted';
    const ACTION_PROCESSING_STARTED = 'processing_started';
    const ACTION_CERTIFICATE_RELEASED = 'certificate_released';
    const ACTION_REQUEST_DECLINED = 'request_declined';
    const ACTION_REQUEST_CANCELLED = 'request_cancelled';
    const ACTION_PUBLIC_DETAILS_UPDATED = 'public_details_updated';

    protected $fillable = [
        'action',
        'action_by',
        'performed_by_name',
        'remarks',
    ];

    public function employeeCoe()
    {
        return $this->belongsTo(EmployeeCoe::class, 'employee_coe_id');
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    public function getActionLabelAttribute()
    {
        $labels = [
            self::ACTION_REQUEST_SUBMITTED => 'Request submitted',
            self::ACTION_PROCESSING_STARTED => 'Processing started',
            self::ACTION_CERTIFICATE_RELEASED => 'Certificate released',
            self::ACTION_REQUEST_DECLINED => 'Request declined',
            self::ACTION_REQUEST_CANCELLED => 'Request cancelled',
            self::ACTION_PUBLIC_DETAILS_UPDATED => 'Public submission details updated',
        ];

        return $labels[$this->action] ?? ucwords(str_replace('_', ' ', $this->action));
    }
}
