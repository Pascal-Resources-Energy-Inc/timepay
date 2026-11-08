<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRetractedAtToEmployeeDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_documents', function (Blueprint $table) {
            $table->timestamp('retracted_at')->nullable()->after('file_name');
            $table->integer('retracted_by')->nullable()->after('retracted_at');
        });
    }
}
