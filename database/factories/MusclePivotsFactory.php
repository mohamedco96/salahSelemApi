<?php

namespace Database\Factories;

use App\Models\MusclePivot;
use Illuminate\Database\Eloquent\Factories\Factory;

class MusclePivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MusclePivot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'muscle_id' => $this->faker->numberBetween(1,16),
            'video_id' => $this->faker->numberBetween(1,100),
        ];
    }
}
