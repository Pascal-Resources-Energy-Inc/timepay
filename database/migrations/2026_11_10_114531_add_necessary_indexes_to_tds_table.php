<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNecessaryIndexesToTdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tds', function (Blueprint $table) {
            $table->index(
                ['deleted_at', 'created_at'],
                'tds_deleted_at_created_at_index'
            );

            $table->index(
                ['deleted_at', 'date_of_registration'],
                'tds_deleted_at_registration_date_index'
            );
        });
    }
}
