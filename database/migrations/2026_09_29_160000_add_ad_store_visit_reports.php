<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdStoreVisitReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_store_visit_reports', function (Blueprint $table) {
            $table->increments('id');

            // Visit information
            $table->string('reference_no', 50)->unique(); // ASVC - 000001
            $table->string('area_distributor', 500);
            $table->string('area_distributor_email', 255)->nullable();
            $table->string('representative_email', 255)->nullable();
            $table->date('visit_date');
            $table->time('time_in');
            $table->time('time_out')->nullable();

            // Discussion summary
            $table->text('issues_and_concerns');
            $table->text('recommendations')->nullable();
            $table->boolean('status')->default(false);
            $table->string('approval_status', 20)->default('pending');
            $table->text('disapproval_reason')->nullable();

            // Submitting officer
            $table->string('submitted_by_name', 255); // HIC
            $table->string('submitted_by_designation', 150); // Designation
            $table->string('submitted_by_email', 255); // officer's email

            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            // indexes
            $table->index('area_distributor_email');
            $table->index('visit_date');
            $table->index('user_id');
            $table->index('submitted_at');
        });

        Schema::create('ad_store_visit_point_persons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ad_store_visit_report_id');

            // Area Distributor | AD Representative 1 | AD Representative 2
            $table->string('role', 50);
            $table->string('full_name', 255)->nullable();
            $table->boolean('is_present')->default(false);
            $table->text('comment')->nullable();
            // $table->timestamps();

            $table->index('role');
        });

        // product availability
        Schema::create('ad_store_visit_product_availabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ad_store_visit_report_id');

            // Products are the fixed checklist items from the ASVC form.
            $table->string('product_name', 255);
            $table->unsignedTinyInteger('sort_order');
            $table->boolean('is_inspected')->default(false);
            $table->boolean('is_available')->nullable();
            $table->text('remarks')->nullable();
            // $table->timestamps();

            $table->index('product_name');
        });

        Schema::create('ad_store_visit_acquired_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ad_store_visit_report_id');

            $table->string('customer_name', 255);
            // End User | Dealer | Mega Dealer
            $table->string('customer_type', 50);
            $table->decimal('amount_purchased', 14, 2);
            // $table->unsignedTinyInteger('sort_order');
            // $table->timestamps();

            $table->index('customer_type');
        });

        Schema::create('ad_store_visit_other_matters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ad_store_visit_report_id');

            // Stores each fixed question from the Other Matters checklist.
            $table->boolean('is_inspected')->default(false);
            $table->string('matter', 255);
            // $table->unsignedTinyInteger('sort_order');
            $table->boolean('status')->nullable();
            $table->text('remarks')->nullable();
            // $table->timestamps();
        });

        Schema::create('ad_store_visit_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ad_store_visit_report_id');

            $table->string('file_path', 500);
            $table->string('original_name', 255);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }
}
