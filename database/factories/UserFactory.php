<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Lottery;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstNames = [
            'Michael',
            'John',
            'Jane',
            'Jim',
            'Jill',
            'Jack',
        ];
        $lastNames = [
            'McDonald',
            'Smith',
            'Johnson',
            'Williams',
            'Brown',
            'Jones',
        ];
        $fn = $firstNames[array_rand($firstNames)];
        $ln = $lastNames[array_rand($lastNames)];

        return [
            'first_name' => $fn,
            'last_name' => $ln,
            'initials' => strtoupper(substr($fn, 0, 1) . substr($ln, 0, 1)),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'timezone' => fake()->timezone(),
            'settings' => [
                'notifications' => [
                    'daily_tasks_due' => true,
                    'weekly_tasks_due' => true,
                    'tasks_assigned' => true,
                    'tasks_updated' => true,
                    'tasks_stale_7_days' => true,
                    'tasks_stale_30_days' => true,
                ],
            ],
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
