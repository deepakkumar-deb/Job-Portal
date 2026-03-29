<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name,
            'user_id' =>1,
            'job_type_id' => rand(1, 5),
            'category_id' => rand(1, 5),
            'vacancy' => rand(1, 50),
            'location' => fake()->city,
            'description' => fake()->text,
            'experience' => fake()->sentence,
            'company_name' => fake()->name,
        ];
    }
}
