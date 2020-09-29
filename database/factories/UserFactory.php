<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $goals = $this->faker->randomElement(['Loss weight', 'Gain weight']);
        $activity = $this->faker->randomElement(['Sedentary', 'Lightly Active', 'Active', 'Very Active']);
        $training_type = $this->faker->randomElement(['home', 'gym']);
        return [
            'role_id' => $this->faker->numberBetween(1,4),
                'social_id' => $this->faker->unique()->creditCardNumber,
                'email' => $this->faker->unique()->safeEmail,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'fname' => $this->faker->firstName($gender),
                'lname' => $this->faker->lastName($gender),
                'name' => $this->faker->userName,
                'age' => $this->faker->numberBetween(10,100),
                'gender' => $gender,
                'height' => $this->faker->numberBetween(100,200),
                'weight' => $this->faker->numberBetween(50,300),
                'neck_size' => $this->faker->numberBetween(10,50),
                'waist_size' => $this->faker->numberBetween(10,50),
                'hips' => $this->faker->numberBetween(10,50),
                'goals' => $goals,
                'activity' => $activity,
                'days_of_training' => $this->faker->numberBetween(1,7),
                'training_type' => $training_type,
                'Water' => $this->faker->numberBetween(10,20),
                'online' => $this->faker->numberBetween(0,1),
                'avatar' => $this->faker->imageUrl($width = 200, $height = 200, 'people', true, 'Faker'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                // 'emp_name' => $faker->name,
                // 'salary'   => $faker->numberBetween($min = 5000, $max = 90000),
                // 'job_description'=> $faker->paragraph
        ];
    }
}
