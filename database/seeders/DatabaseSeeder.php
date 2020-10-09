<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(100000)->create();
        // \App\Models\Article::factory(100)->create();
        // \App\Models\Video::factory(100)->create();
        // \App\Models\Recipes::factory(100)->create();
        // \App\Models\Videocategoriespivot::factory(100)->create();
        // \App\Models\VideoTypePivot::factory(100)->create();
        // \App\Models\VideoTagPivot::factory(100)->create();
        \App\Models\MusclePivot::factory(100)->create();




    }
 
}
