<?php

namespace Database\Factories;

use App\Models\VideoTypePivot;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoTypePivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VideoTypePivot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'video_type_id' => $this->faker->numberBetween(1,2),
            'video_id' => $this->faker->numberBetween(1,100),
        ];
    }
}
