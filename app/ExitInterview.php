<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ExitInterview extends Model implements Auditable {
    use \OwenIt\Auditing\Auditable;

    protected $table = 'exit_interviews';

    protected $fillable = [
        'reference_no',
        'legacy_reference_no',
        'first_name', 'last_name', 'full_name', 'email',
        'job_title', 'department', 'direct_supervisor_name', 'work_location',
        'hire_date', 'separation_date',
        'is_separation_voluntary', 'separation_type', 'separation_comment',
        'is_leaving_for_new_job', 'reason_of_leave', 'reason_of_leave_other', 'career_type_importance',
        'new_job_offer_text',
        'rating_job_responsibilities', 'rating_personal_goals', 'rating_work_environment',
        'rating_immediate_superior', 'rating_salary', 'rating_benefits',
        'most_liked_text', 'least_liked_text',
        'understands_company_goals', 'company_lives_core_values',
        'goals_explanation', 'core_values_explanation',   
        'frequently_performance_feedback',
        'suggestion_company', 'suggestion_department', 'suggestion_other_employees',
        'other_feedback_text',
        'submitted_at',
        'employee_id', 'user_id',
        'submission_source',
        'employee_number'
    ];

    protected $casts = [
        'is_separation_voluntary'    => 'boolean',
        'is_leaving_for_new_job'     => 'boolean',
        'understands_company_goals'  => 'boolean',
        'company_lives_core_values'  => 'boolean',
        'hire_date'                  => 'date',
        'separation_date'            => 'date',
        'submitted_at'               => 'datetime',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
