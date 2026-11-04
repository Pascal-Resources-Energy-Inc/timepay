<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarketingMaterialReleaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_material_releases', function (Blueprint $table) {
            $table->increments('id');

            // SUBMISSION REFERENCE
            $table->string('reference_no', 30)->unique(); // MMR-0001

            // PERSONAL INFORMATION
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('birth_date');
            $table->string('gender', 10); // Male | Female | N/A
            $table->string('phone_number', 30);
            $table->string('email', 255);

            // NAME-USAGE PREFERENCES
            $table->boolean('consent_complete_name')->default(false);
            $table->boolean('consent_first_name_only')->default(false);
            $table->boolean('consent_nickname')->default(false);
            $table->boolean('consent_anonymous_photos')->default(false);

            // AUTHORIZATION AND RELEASE
            $table->boolean('authorize_capture')->default(false);
            $table->boolean('authorize_social_media')->default(false);
            $table->boolean('authorize_editing')->default(false);
            $table->boolean('agree_company_ip_ownership')->default(false);
            $table->boolean('agree_no_compensation')->default(false);

            // SIGNING INFORMATION
            $table->longText('signature'); // Drawn signature data URL
            $table->date('date_signed');

            // WORKFLOW
            $table->string('status', 50)->default('For Approval');
            $table->timestamp('approved_date')->nullable();
            $table->text('approval_remarks')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('edited_at')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index('status');
            $table->index('email');
            $table->index('user_id');
            $table->index('submitted_at');
        });
    }

}
