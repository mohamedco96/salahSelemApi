<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoTagPivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_tag_pivots', function (Blueprint $table) {
            $table->unsignedBigInteger('video_tag_id');
            $table->unsignedBigInteger('video_id');
            $table->foreign('video_tag_id')->references('id')->on('video_tags');
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
        Schema::dropIfExists('video_tag_pivots');
    }
}
