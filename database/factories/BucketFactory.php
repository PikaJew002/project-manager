<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bucket>
 */
class BucketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bucketNames = [
            'Design Tasks',
            'Development Tasks',
            'QA Tasks',
            'Documentation Tasks',
            'Support Tasks',
        ];

        $bucketName = $bucketNames[array_rand($bucketNames)];

        return [
            'name' => $bucketName,
        ];
    }
}
