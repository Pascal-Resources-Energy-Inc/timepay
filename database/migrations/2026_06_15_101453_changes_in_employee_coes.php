<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangesInEmployeeCoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_coes', function (Blueprint $table) {
            $table->string('first_name', 255)->after('applied_date');
            $table->string('last_name', 255)->after('first_name');

            $table->unsignedInteger('user_id')->nullable()->change();
            $table->unsignedInteger('created_by')->nullable()->change(); // for internal
            $table->unsignedInteger('schedule_id')->nullable()->change();

            $table->string('reference_number', 20)->nullable()->after('id');
            $table->string('viber_number', 11)->nullable()->after('email');
            $table->string('gender', 10)->nullable()->after('name');
            $table->date('resign_date')->nullable()->after('hiring_date');
            $table->string('attachment', 255)->nullable()->after('additional_notes');
            $table->string('proof', 255)->nullable()->after('attachment');

            $table->timestamp('processed_at')->nullable();
        });
    }

}
