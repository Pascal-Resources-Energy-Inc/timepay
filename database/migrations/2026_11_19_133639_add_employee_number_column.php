<?php

use FontLib\Table\Table;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeNumberColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('bmc', function (Blueprint $table) {
            $table->string('employee_number', 30)->nullable()->after('reference_no');
        });
    }
}
