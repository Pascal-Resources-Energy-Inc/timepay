<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIurAccountabilitiesTable extends Migration
{
    public function up()
    {
        Schema::create('iur_accountabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iur_id')->index();
            $table->string('accountability_ref')->unique();

            // released and its condition
            $table->integer('released_prod_qty')->default(0);
            $table->string('prod_condition')->nullable();
            $table->integer('released_white_qty')->default(0);
            $table->string('white_condition')->nullable();
            $table->integer('released_black_qty')->default(0);
            $table->string('black_condition')->nullable();
            $table->integer('released_collar_qty')->default(0);
            $table->string('collar_condition')->nullable();

            $table->text('notes')->nullable(); // issuance notes

            $table->boolean('released_id')->default(false);
            $table->integer('issued_by')->nullable()->index();
            $table->timestamp('issued_date');
            $table->string('status')->default('Released');
            $table->timestamp('received_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('employee_signature', 255)->nullable();
            $table->timestamps();

        });
    }
}
