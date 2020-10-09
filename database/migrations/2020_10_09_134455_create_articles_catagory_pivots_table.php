<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesCatagoryPivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_catagory_pivots', function (Blueprint $table) {
            $table->unsignedBigInteger('articles_catagory_id');
            $table->unsignedBigInteger('article_id');
            $table->foreign('articles_catagory_id')->references('id')->on('articles_catagories');
            $table->foreign('article_id')->references('id')->on('articles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_catagory_pivots');
    }
}
