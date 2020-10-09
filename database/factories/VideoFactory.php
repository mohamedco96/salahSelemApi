<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $video_Link = $this->faker->randomElement(['https://media.giphy.com/media/TTPi3fB9F5Aqs/giphy.gif', 'https://media.giphy.com/media/WsjvRxj8RRxYZgIzzI/giphy.gif', 'https://media.giphy.com/media/sLs8Ll8Qx51xm/giphy.gif', 'https://media.giphy.com/media/yBjUwriEYpFyE/giphy.gif']);
        // $catagory = $this->faker->randomElement(['fitness', 'cardio', 'yoga']);
        // $type = $this->faker->randomElement(['home', 'gym', 'No weights', 'weights']);

        return [
            'video_Name' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'video_Description'=> $this->faker->paragraph,
            'video_thumbnail' => $this->faker->imageUrl($width = 300, $height = 300, 'sports', true, 'Faker'),
            'video_Link' => $video_Link,
            'video_Quote' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            // 'catagory' => $catagory,
            // 'type' => $type,
            'video_Reps' => $this->faker->numberBetween(10,50),
            'video_Sets' => $this->faker->numberBetween(10,20),
        ];
    }
}
