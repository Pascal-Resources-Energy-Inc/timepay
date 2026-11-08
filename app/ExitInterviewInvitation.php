<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitInterviewInvitation extends Model {
    protected $table = 'exit_interview_invitations';

    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'recipient_email',
        'sent_by',
        'sent_at',
        'completed_at',
        'exit_interview_id',
        'delivery_error',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}
