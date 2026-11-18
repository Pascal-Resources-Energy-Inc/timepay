<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBmcItemSerials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bmc_accountability_item_serials', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('bmc_accountability_item_id');
            $table->string('serial_number', 150);
            $table->string('status', 30)->default('Available');

            // remarks of two parties
            $table->text('mbd_remarks')->nullable();
            $table->text('borrower_remarks')->nullable();

            $table->timestamp('returned_at')->nullable();
            $table->unsignedInteger('returned_by')->nullable();

            $table->timestamps();

            $table->index('bmc_accountability_item_id');
            $table->index('serial_number');
            $table->index('status');
        });
    }
}
