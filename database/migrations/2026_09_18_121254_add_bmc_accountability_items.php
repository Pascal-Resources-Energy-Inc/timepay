<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBmcAccountabilityItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bmc_accountability_items', function (Blueprint $table) {
            $table->increments('id');

            // the release record containing this item
            $table->unsignedInteger('bmc_accountability_id');

            // released item information
            $table->string('item_name', 255);
            $table->unsignedInteger('released_quantity')->default(1);
            $table->string('serial_number', 100)->nullable();
            $table->string('condition_on_release', 50)->nullable();

            // Preserve the deposit value at the time of release
            $table->decimal('deposit_amount', 10, 2)->default(0);

            // return information
            $table->unsignedInteger('returned_quantity')->default(0);
            $table->string('condition_on_return', 50)->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->text('return_remarks')->nullable();

            $table->timestamps();

            $table->foreign('bmc_accountability_id')
                  ->references('id')
                  ->on('bmc_accountabilities')
                  ->onDelete('cascade');

            $table->index('item_name');
            $table->index('serial_number');
        });
    }
}
