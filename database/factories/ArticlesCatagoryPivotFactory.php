<?php

namespace Database\Factories;

use App\Models\ArticlesCatagoryPivot;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticlesCatagoryPivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticlesCatagoryPivot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'articles_catagory_id' => $this->faker->numberBetween(1,4),
            'article_id' => $this->faker->numberBetween(1,100),
        ];
    }
}
