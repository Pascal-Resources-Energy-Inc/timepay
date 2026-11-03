<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIssuanceRemarksToEmployeeCoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_coes', function (Blueprint $table) {
            $table->text('issuance_remarks')->nullable()->after('proof');
        });
    }
}
