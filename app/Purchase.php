<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';
    
    protected $fillable = [
        'order_number',
        'user_id',
        'employee_number',
        'employee_name',
        'employee_work_place',
        'qty_330g',
        'qty_230g',
        'total_items',
        'subtotal',
        'discount',
        'total_amount',
        'payment_method',
        'status',
        'qr_code',
        'notes',
        'created_at',
        'updated_at',
    ];
}