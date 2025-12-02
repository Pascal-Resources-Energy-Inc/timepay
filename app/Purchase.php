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
        'total_items',
        'subtotal',
        'discount',
        'total_amount',
        'payment_method',
        'status',
        'qr_code',
        'claimed_at',
        'claim_latitude',
        'claim_longitude',
        'si',
        'claim_address',
        'giver_name',
        'giver_position',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_number', 'employee_number');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id', 'id');
    }
}