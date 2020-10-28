<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('video_Name');
            $table->string('catagory');
            $table->string('type');
            $table->string('video_thumbnail');
            $table->string('video_Link');
            $table->text('video_Description')->nullable();
            $table->string('video_Quote')->nullable();
            $table->integer('video_Reps')->nullable();
            $table->integer('video_Sets')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
