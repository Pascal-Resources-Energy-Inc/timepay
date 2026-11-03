<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeCoeTrailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_coe_trails', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('employee_coe_id')->index();
            $table->string('action', 50);
            $table->unsignedInteger('action_by')->nullable()->index();
            $table->string('performed_by_name', 255);
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(['employee_coe_id', 'created_at']);
        });
    }

}
