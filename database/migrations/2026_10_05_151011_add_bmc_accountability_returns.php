<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBmcAccountabilityReturns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bmc_accountability_returns', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('bmc_accountability_id');
            $table->unsignedInteger('bmc_accountability_item_id');

            $table->unsignedInteger('returned_quantity');
            $table->text('serial_numbers')->nullable();

            $table->timestamp('returned_at');
            $table->string('return_mode', 50);

            $table->unsignedInteger('received_by')->nullable();
            $table->string('condition_on_return', 50)->nullable();
            $table->text('attachment')->nullable(); // image attachment
            $table->text('borrower_remarks')->nullable();
            $table->text('approver_remarks')->nullable();

            $table->timestamps();

            $table->index('returned_at');
            $table->index('return_mode');
        });
    }

}
