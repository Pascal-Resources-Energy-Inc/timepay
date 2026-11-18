<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarketingCollateralBorrowings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bmc', function (Blueprint $table) {
            $table->increments('id');

            $table->string('reference_no'); // BMC - 0001

            // I. Borrower's Information
            $table->string('borrower_profile', 50); // Provincial Distributor | Area Distributor | PREI
            $table->string('borrower_first_name', 255)->nullable();
            $table->string('borrower_last_name', 255)->nullable();
            $table->string('full_name', 255)->nullable(); // legacy data
            $table->string('department', 100)->nullable();
            $table->string('business_name', 255)->nullable();
            $table->string('designation', 100)->nullable();
            $table->string('email', 255);
            $table->string('phone_number', 30); 
            $table->string('location', 255)->nullable(); 

            // deposit
            $table->json('release_items')->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->string('proof_of_transfer', 255)->nullable();

            // deposit refund 
            $table->string('refund_payment_method', 100)->nullable();
            $table->string('proof_of_refund', 255)->nullable();

            // II. Event Details
            $table->date('date_needed');
            $table->string('event_name', 255);
            $table->text('request_details');
            $table->unsignedTinyInteger('number_of_days_item_needed'); // max 40
            $table->unsignedTinyInteger('approved_number_of_days')->nullable();

            $table->string('status', 50)->default('For Approval');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->text('mbd_remarks')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('user_id');
            $table->index('created_at');
        });
    }
}
