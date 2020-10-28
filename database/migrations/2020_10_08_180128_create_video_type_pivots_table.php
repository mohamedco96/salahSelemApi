<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoTypePivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_type_pivots', function (Blueprint $table) {
            $table->unsignedBigInteger('video_type_id');
            $table->unsignedBigInteger('video_id');
            $table->foreign('video_type_id')->references('id')->on('video_types');
            $table->foreign('video_id')->references('id')->on('videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_type_pivots');
    }
}
