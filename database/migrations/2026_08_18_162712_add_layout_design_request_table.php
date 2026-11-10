<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLayoutDesignRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout_design_requests', function (Blueprint $table) {
            $table->increments('id');

            $table->string('reference_no'); // LDR - 0001

            // REQUEST INFORMATION
            $table->timestamp('date_requested')->useCurrent();
            $table->string('requestor_first_name', 255)->nullable();
            $table->string('requestor_last_name', 255)->nullable();
            $table->string('full_name', 255)->nullable(); // for legacy data
            $table->string('department', 100); // PD | AD | CEO | OOP | BDD | BSD | CMD | CAD | HRD | IAD | ITD | MBD | POD | PQD | PCD | RSD | TRD
            $table->string('requestor_email', 255);
            $table->text('intended_use'); 
            $table->string('medium_type', 50); // Digital Media | Print Media
            $table->text('marketing_collateral_type'); // Business Card | Announcement | Poster | Tarpaulin | Promotional Item | Video | Other
            $table->text('purpose_of_layout_request'); // Where it will be used
            $table->text('project_description'); // e.g. text to include, size, quantity
            $table->string('priority_level', 20)->nullable(); // e.g., minor & major
            $table->string('peg_sample_path', 255)->nullable(); // Uploaded peg sample file

            // for dms
            $table->string('business_name', 255)->nullable();
            $table->string('ad_territory', 255)->nullable();
            $table->string('business_address', 255)->nullable();
            $table->string('contact_number', 11)->nullable();
            $table->text('additional_notes')->nullable();

            $table->timestamp('date_needed')->nullable();
            $table->dateTime('due_date')->nullable();

            $table->string('status', 50)->default('Pending');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->text('remarks')->nullable();

            $table->date('issuance_date')->nullable();
            $table->text('closing_remarks')->nullable();
            $table->string('output_attachment', 255)->nullable();

            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index('status');
            $table->index('due_date');
            $table->index('user_id');
            $table->index('submitted_at');
            $table->index('date_needed');
        });
    }

}
