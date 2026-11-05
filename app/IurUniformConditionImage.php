<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IurUniformConditionImage extends Model
{
    protected $table = 'iur_uniform_condition_images';

    protected $fillable = [
        'iur_id',
        'image_path',
    ];

    public function iur()
    {
        return $this->belongsTo(IUR::class, 'iur_id');
    }
}
