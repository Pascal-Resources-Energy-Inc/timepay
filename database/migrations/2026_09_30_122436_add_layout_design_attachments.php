<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLayoutDesignAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        /* Attachments that will be send to requestor will store here */

        Schema::create('layout_design_attachments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('layout_design_request_id');

            $table->string('output_attachment_path', 1000);
            $table->string('original_name', 255)->nullable();
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->timestamps();

            $table->index('layout_design_request_id');
        });    
    }
}
