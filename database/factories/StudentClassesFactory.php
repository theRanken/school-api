<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentClasses>
 */
class StudentClassesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $classes = ['JSS1', 'JSS2', 'JSS3', 'SS1', 'SS2', 'SS3'];
        return [
            'class' => $this->faker->unique->randomElement($classes),
            'fees' => doubleval(rand(30000, 99999))
        ];
    }
}
