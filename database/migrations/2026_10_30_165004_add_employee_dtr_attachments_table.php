<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeDtrAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_dtr_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_dtr_id');
            $table->string('file_path', 500);
            $table->timestamps();

            $table->index('employee_dtr_id');
        });
    }
}
