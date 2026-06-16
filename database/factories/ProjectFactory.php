<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projectNames = [
            'Design Team Project',
            'Development Team Project',
            'QA Team Project',
            'Documentation Team Project',
            'Support Team Project',
        ];

        $projectName = $projectNames[array_rand($projectNames)];

        $initials = Str::of($projectName)->substr(0, 2)->upper()->toString();

        return [
            'name' => $projectName,
            'initials' => $initials,
        ];
    }
}
