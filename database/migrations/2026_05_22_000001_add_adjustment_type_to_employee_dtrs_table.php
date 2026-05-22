<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdjustmentTypeToEmployeeDtrsTable extends Migration
{
    public function up()
    {
        Schema::table('employee_dtrs', function (Blueprint $table) {
            $table->string('adjustment_type')->nullable()->after('correction');
        });
    }

    public function down()
    {
        Schema::table('employee_dtrs', function (Blueprint $table) {
            $table->dropColumn('adjustment_type');
        });
    }
}
