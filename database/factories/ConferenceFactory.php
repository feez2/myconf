<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conference>
 */
class ConferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 year')->format('Y-m-d');

        return [
            'title' => $this->faker->sentence(4),
            'acronym' => strtoupper($this->faker->lexify('???')),
            'description' => $this->faker->paragraphs(3, true),
            'location' => $this->faker->city,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'submission_deadline' => $this->faker->dateTimeBetween('-1 month', $startDate)->format('Y-m-d'),
            'review_deadline' => $this->faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d'),
            'website' => $this->faker->url,
            'status' => $this->faker->randomElement(['upcoming', 'ongoing', 'completed']),
        ];
    }
}
