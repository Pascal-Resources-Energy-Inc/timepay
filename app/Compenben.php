<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compenben extends Model {
    protected $table = 'compenben';

    protected $fillable = [ 
        'reference_no',
        'employee_code',
        'first',
        'last',
        'email',
        'phone',
        'designation',
        'work_location',
        'issue',
        'details',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
