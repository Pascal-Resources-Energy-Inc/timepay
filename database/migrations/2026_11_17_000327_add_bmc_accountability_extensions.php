<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBmcAccountabilityExtensions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bmc_accountability_extensions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bmc_accountability_id');

            $table->date('current_return_date');
            $table->date('requested_return_date');
            $table->text('reason');
            $table->integer('requested_by')->nullable();

            // pending | approved | declined
            $table->string('status', 20)->default('pending');
            $table->integer('action_by')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(
                ['bmc_accountability_id', 'status'],
                'bmc_extensions_release_status_idx'
            );
        });
    }

}
