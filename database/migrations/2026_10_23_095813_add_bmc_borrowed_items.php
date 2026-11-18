<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBmcBorrowedItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bmc_borrowed_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('marketing_collateral_borrowing_id');
            $table->string('item_name', 255);
            $table->unsignedInteger('requested_quantity')->default(1);
            $table->unsignedInteger('approved_quantity')->nullable();
            $table->timestamps();

            $table->index('marketing_collateral_borrowing_id');
            $table->index('item_name');
        });
    }
}
