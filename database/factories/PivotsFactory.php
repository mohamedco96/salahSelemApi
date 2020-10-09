<?php

namespace Database\Factories;

use App\Models\Videocategoriespivot;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideocategoriespivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Videocategoriespivot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'video_catagory_id' => $this->faker->numberBetween(1,6),
            'video_id' => $this->faker->numberBetween(1,100),
        ];
    }
}
