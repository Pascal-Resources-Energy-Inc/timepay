<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HubPerLocation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'hub_per_location';
    
    protected $fillable = [
        'no',
        'region',
        'territory',
        'area', 
        'hub_name',
        'hub_code',
        'retail_hub_address',
        'hub_status',
        'google_map_location_link',
        'lat',
        'long',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByTerritory($query, $territory)
    {
        return $query->where('territory', $territory);
    }

    public function scopeByArea($query, $area)
    {
        return $query->where('area', $area);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('hub_status', $status);
    }

    public function scopeActive($query)
    {
        return $query->where('hub_status', 'Active');
    }

    // Accessors
    public function getFormattedAddressAttribute()
    {
        return str_limit($this->retail_hub_address, 50);
    }

    public function getHasGoogleMapAttribute()
    {
        return !empty($this->google_map_location_link);
    }

    public function getStatusBadgeClassAttribute()
    {
        switch (strtolower($this->hub_status)) {
            case 'active':
                return 'badge-success';
            case 'inactive':
                return 'badge-danger';
            case 'pending':
                return 'badge-warning';
            default:
                return 'badge-secondary';
        }
    }

    public function getFormattedLatAttribute()
    {
        return $this->lat ? number_format((float)$this->lat, 6) : 'N/A';
    }

    public function getFormattedLongAttribute()
    {
        return $this->long ? number_format((float)$this->long, 6) : 'N/A';
    }
}