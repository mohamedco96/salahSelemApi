<?php

namespace Database\Factories;

use App\Models\RecipesCategoryPivot;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipesCategoryPivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RecipesCategoryPivot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recipes_catagory_id' => $this->faker->numberBetween(1,4),
            'recipes_id' => $this->faker->numberBetween(1,100),
        ];
    }
}
