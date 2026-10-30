<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeDtrAttachment extends Model {
    protected $fillable = [ 
        'employee_dtr_id',
        'file_path'
    ];

    public function employeeDtr() {
        return $this->belongsTo(EmployeeDtr::class, 'employee_dtr_id', 'id');
    }
}
