<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model {
    protected $casts = [
        'retracted_at' => 'datetime'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function retractedBy() {
        return $this->belongsTo(User::class, 'retracted_by');
    }
}
