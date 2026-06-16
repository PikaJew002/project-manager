<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taskNames = [
            'Review the design document',
            'Create the technical specifications',
            'Meet with the client to discuss the design mockups',
            'Develop the user interface',
        ];

        return [
            'name' => $taskNames[array_rand($taskNames)],
        ];
    }
}
