<?php

namespace Database\Factories;

use App\Models\ArticleTagPivot;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleTagPivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleTagPivot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_tag_id' => $this->faker->numberBetween(1,4),
            'article_id' => $this->faker->numberBetween(1,100),
        ];
    }
}
