<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLegacyRefNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_coes', function (Blueprint $table) {
            $table->string('legacy_reference_number', 50)
                  ->nullable()
                  ->unique()
                  ->after('reference_number');
        });
    }
}
