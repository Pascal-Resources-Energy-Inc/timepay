<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttachmentRemarksToEmployeeCoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_coes', function (Blueprint $table) {
            $table->text('attachment_remarks')->nullable()->after('attachment');
        });
    }
}
