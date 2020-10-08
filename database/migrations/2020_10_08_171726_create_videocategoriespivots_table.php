<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideocategoriespivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videocategoriespivots', function (Blueprint $table) {
            $table->unsignedBigInteger('video_catagory_id');
            $table->unsignedBigInteger('video_id');
            $table->foreign('video_catagory_id')->references('id')->on('video_catagories');
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
        Schema::dropIfExists('videocategoriespivots');
    }
}
