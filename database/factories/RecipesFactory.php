<?php

namespace Database\Factories;

use App\Models\Recipes;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $catagory = $this->faker->randomElement(['cat1', 'cat2', 'Text Text']);

        return [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'catagory' => $catagory,
            'thumbnail' => $this->faker->imageUrl($width = 300, $height = 300, 'food', true, 'Faker'),
            'image' => $this->faker->imageUrl($width = 640, $height = 480, 'food', true, 'Faker'),
            'content' => $this->faker->paragraph,
            'calories' => $this->faker->numberBetween(0,100),
            'fat' => $this->faker->numberBetween(0,100),
            'protein' => $this->faker->numberBetween(0,100),
            'carb' => $this->faker->numberBetween(0,100),
        ];
    }
}
