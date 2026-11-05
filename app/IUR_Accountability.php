<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IUR_Accountability extends Model
{
    protected $table = 'iur_accountabilities';

    protected $fillable = [
        'iur_id', 
        'accountability_ref', 
        'released_prod_qty', 
        'released_white_qty',
        'released_black_qty', 
        'released_collar_qty', 
        'released_id',
        'issued_by', 
        'issued_date', 
        'status', 
        'received_at', 
        'signed_at', 
        'employee_signature',

        'prod_condition',
        'white_condition',
        'black_condition',
        'collar_condition',
        'notes'
    ];

    protected $dates = ['signed_at'];

    public function iur()
    {
        return $this->belongsTo(IUR::class, 'iur_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
