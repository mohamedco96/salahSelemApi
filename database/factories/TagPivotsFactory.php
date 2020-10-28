<?php

namespace Database\Factories;

use App\Models\VideoTagPivot;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoTagPivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VideoTagPivot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'video_tag_id' => $this->faker->numberBetween(1,2),
            'video_id' => $this->faker->numberBetween(1,100),
        ];
    }
}
