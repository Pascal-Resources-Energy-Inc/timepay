<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';

    protected $fillable = ['region_code', 'region_name'];

    public function tdsRecords()
    {
        return $this->hasMany(Tds::class, 'area');
    }
}