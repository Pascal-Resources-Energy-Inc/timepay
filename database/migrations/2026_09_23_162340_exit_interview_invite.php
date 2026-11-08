<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExitInterviewInvite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('exit_interview_invitations', function (Blueprint $table) {
            $table->increments('id');

            // snapshot in case employee information changes later
            $table->string('employee_number', 50)->nullable();
            $table->string('first_name', 200)->nullable();
            $table->string('last_name', 200)->nullable();
            $table->string('recipient_email', 255);

            // sender
            $table->unsignedInteger('sent_by');

            $table->dateTime('sent_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            // Populated after successful exit-interview submission
            $table->unsignedInteger('exit_interview_id')->nullable();

            // Records an email-delivery problem
            $table->text('delivery_error')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('employee_number');
            $table->index('recipient_email');
            $table->index('sent_by');
        });
    }
}
