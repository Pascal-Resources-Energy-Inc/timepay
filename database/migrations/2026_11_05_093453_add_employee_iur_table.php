<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeIurTable extends Migration {
    public function up() {
        Schema::create('employee_iur', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iur_reference', 255)->nullable();

            // legacy column
            $table->string('legacy_reference', 255)->nullable();
            $table->string('legacy_first', 255)->nullable();
            $table->string('legacy_last', 255)->nullable();
            $table->string('legacy_designation', 255)->nullable();
            $table->date('legacy_date_hired')->nullable();

            $table->integer('user_id')->nullable();

            $table->string('type', 255)->nullable(); // new hire / active
            $table->string('work_location', 255)->nullable();
            $table->string('request_for', 255)->nullable(); // both, id, uniform
            $table->longText('details')->nullable();
            $table->string('issued', 255)->nullable(); // yes | no
            $table->string('issued_remarks', 255)->nullable(); // how many was issued before
            $table->text('issued_reasons')->nullable(); // reason
            $table->string('size', 255)->nullable(); // e.g s, m , L

            // quantities
            $table->integer('white_qty')->default(0);
            $table->integer('received_white_qty')->default(0);
            $table->integer('black_qty')->default(0);
            $table->integer('received_black_qty')->default(0);
            $table->integer('collar_qty')->default(0);
            $table->integer('received_collar_qty')->default(0);
            $table->integer('prod_qty')->default(0);
            $table->integer('received_prod_qty')->default(0);

            $table->string('notes', 255)->nullable(); // additional notes
            $table->string('id_request', 255)->nullable(); // reason for requesting id
            $table->string('id_picture', 255)->nullable(); // image itself
            $table->string('uniform_condition_image', 255)->nullable(); // image proof of uniform condition
            $table->string('status', 255)->nullable(); // e.g pending, processing, partial, released, cancel, decline

            $table->integer('created_by')->nullable(); // linked to user_id
            $table->date('approved_date')->nullable();
            $table->string('approval_remarks', 255)->nullable();
            $table->text('e_signature')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->integer('approved_by')->nullable();
            $table->timestamp('id_printed_at')->nullable();
            $table->unsignedInteger('id_print_count')->default(0);
            $table->json('requestor_messages')->nullable();
            $table->timestamps();
        });
    }
}
