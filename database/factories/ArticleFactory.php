<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $author = $this->faker->randomElement(['6011988130179138', '2720442380587609', '2221557311738999', '2475943541200039']);
        $catagory = $this->faker->randomElement(['catagory1', 'catagory2', 'catagory3', 'catagory4']);
        $tag = $this->faker->randomElement(['tag1', 'tag2', 'tag3', 'tag4']);

        return [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'author' => $author,
            'catagory' => $catagory,
            'tag' => $tag,
            'thumbnail' => $this->faker->imageUrl($width = 300, $height = 300, 'sports', true, 'Faker'),
            'image' => $this->faker->imageUrl($width = 640, $height = 480, 'sports', true, 'Faker'),
            'content' => $this->faker->paragraph,
        ];
    }
}
