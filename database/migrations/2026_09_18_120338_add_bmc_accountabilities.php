<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBmcAccountabilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bmc_accountabilities', function (Blueprint $table) {
            $table->increments('id');

            // Parent BMC request
            $table->unsignedInteger('marketing_collateral_borrowing_id');

            // Example: BMC-000001-1
            $table->string('accountability_ref', 50)->unique();

            // Release information
            $table->integer('released_by');
            $table->timestamp('released_at');
            $table->date('expected_return_at')->nullable();
            $table->text('release_notes')->nullable(); // issuance notes
            $table->text('mor')->nullable(); // mode of return (issuance notes)

            // deposit
            $table->string('payment_method', 100)->nullable();
            $table->string('proof_of_transfer', 255)->nullable();

            // Released | Closed
            $table->string('status', 30)->default('Released');

            // Borrower's acknowledgement
            $table->string('borrower_signature')->nullable();
            $table->timestamp('signed_at')->nullable();

            // Closing information
            $table->integer('closed_by')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('closing_remarks')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('released_at');
        });
    }
}
