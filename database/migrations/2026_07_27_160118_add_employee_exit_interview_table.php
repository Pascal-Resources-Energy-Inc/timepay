<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeExitInterviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exit_interviews', function (Blueprint $table) {
            $table->increments('id');

            $table->string('employee_number', 50)->unique()->nullable();
            $table->string('reference_no', 255)->unique();
            $table->string('legacy_reference_no', 50)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('full_name', 255)->nullable(); // for legacy data
            $table->string('email', 255);

            $table->string('job_title', 150);
            $table->string('department', 150);
            $table->string('direct_supervisor_name', 255);
            $table->string('work_location', 150);
            $table->date('hire_date');
            $table->date('separation_date');

            // Is the separation voluntary?
            $table->boolean('is_separation_voluntary');

            // Required only for involuntary separation
            $table->string('separation_type', 100)->nullable();
            $table->text('separation_comment')->nullable();

            // Is your reason for leaving a new job opportunity?
            $table->boolean('is_leaving_for_new_job')->nullable();
            
            // What does your new job have to offer that your current one does not?
            $table->text('new_job_offer_text')->nullable();

            // what made u decide to leave?
            $table->text('reason_of_leave')->nullable();
            $table->text('reason_of_leave_other')->nullable();

            // What types of career opportunities are important to you?* (select)
            $table->string('career_type_importance');

            // rate 1-5
            $table->unsignedTinyInteger('rating_job_responsibilities')->nullable(); // Job Responsibilities
            $table->unsignedTinyInteger('rating_personal_goals')->nullable();// Opportunities for achieving personal goals
            $table->unsignedTinyInteger('rating_work_environment')->nullable();// work environment
            $table->unsignedTinyInteger('rating_immediate_superior')->nullable();// immediate superior
            $table->unsignedTinyInteger('rating_salary')->nullable();// salary
            $table->unsignedTinyInteger('rating_benefits')->nullable();// benefits

            // what did u like MOST about working here?
            $table->text('most_liked_text');

            // what did u like LEAST about working here?
            $table->text('least_liked_text');

            // Did you have a strong, clear understanding of the main goals and direction of your department and its contribution to the company's overall vision?
            $table->boolean('understands_company_goals');

            // These are our Company's Core Values: Honesty & Integrity, Innovation, Teamwork, Commitment to Serve, Communication and Respect, and Hard work. Do you believe PREI live up to these values?
            $table->boolean('company_lives_core_values');
            $table->text('goals_explanation')->nullable();
            $table->text('core_values_explanation')->nullable();

            // How frequently did you get performance feedback?* (select)
            $table->string('frequently_performance_feedback');

            // any changes u would like to suggest? Give us ur recommendations!
            $table->text('suggestion_company')->nullable();
            $table->text('suggestion_department')->nullable();
            $table->text('suggestion_other_employees')->nullable();

            $table->text('other_feedback_text')->nullable();

            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('submission_source', 20)->default('internal');

            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            /* indexes */
            $table->index('department');
            $table->index('submitted_at');
            $table->index('employee_id');
            $table->index('user_id');
        });
    }
}
