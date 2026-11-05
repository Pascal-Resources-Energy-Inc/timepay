<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompenbenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compenben', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('reference_no', 30);

            $table->string('employee_code', 10);
            $table->string('first', 255);
            $table->string('last', 255);
            $table->string('email', 255);
            $table->string('phone', 11);

            $table->string('designation', 255);

            $table->string('work_location', 255);
            $table->string('issue', 255);
            $table->text('details');
            $table->timestamps();
        });
    }
}
