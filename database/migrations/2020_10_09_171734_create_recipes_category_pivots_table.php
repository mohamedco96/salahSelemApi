<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesCategoryPivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes_category_pivots', function (Blueprint $table) {
            $table->unsignedBigInteger('recipes_catagory_id');
            $table->unsignedBigInteger('recipes_id');
            $table->foreign('recipes_catagory_id')->references('id')->on('recipes_catagories');
            $table->foreign('recipes_id')->references('id')->on('recipes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes_category_pivots');
    }
}
