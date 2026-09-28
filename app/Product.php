<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    
    protected $fillable = [
        'product_name',
        'price',
        'deposit',
        'discount',
        'product_image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'deposit' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    /**
     * Get all purchase items for this product
     */
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'product_id');
    }

    /**
     * Get full price (price + deposit)
     */
    public function getFullPriceAttribute()
    {
        return $this->price + $this->deposit;
    }

    /**
     * Check if this is a main product
     */
    public function isMainProduct()
    {
        return in_array($this->id, [12, 13]); // BBQ GRILL products
    }
}