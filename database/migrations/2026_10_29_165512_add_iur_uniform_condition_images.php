<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIurUniformConditionImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iur_uniform_condition_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iur_id')->index();
            $table->string('image_path', 255);
            $table->timestamps();
        });
    }
}
