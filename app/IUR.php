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
        'uniform_condition_image',
        'status',
        'approved_date',
        'approval_remarks',
        'requestor_messages',
        'approved_by',
        'e_signature',

        'prod_qty',
        'white_qty',
        'black_qty',
        'collar_qty',

        'received_prod_qty',
        'received_white_qty',
        'received_black_qty',
        'received_collar_qty',

        'reminder_sent_at',
        'id_printed_at',
        'id_print_count'
    ];

    protected $casts = [
        'requestor_messages' => 'array',
        'id_printed_at' => 'datetime',
        'id_print_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
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

    public function accountabilities()
    {
        return $this->hasMany(IUR_Accountability::class, 'iur_id');
    }

    public function uniformConditionImages()
    {
        return $this->hasMany(IurUniformConditionImage::class, 'iur_id');
    }
}
