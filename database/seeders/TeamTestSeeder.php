<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user with a personal team
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'initials' => 'TU',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a personal team for the user
        $personalTeam = $user->createOrganization([
            'name' => 'Test User\'s Organization',
            'personal_team' => true,
        ]);

        // Create another team for the user
        $team = $user->createOrganization([
            'name' => 'Test Team',
            'personal_team' => false,
        ]);

        // Create another user to add to the team
        $member = User::create([
            'first_name' => 'Team',
            'last_name' => 'Member',
            'initials' => 'TM',
            'email' => 'member@example.com',
            'password' => bcrypt('password'),
        ]);

        // Add the member to the team
        $team->users()->attach($member->id, ['role' => 'member']);

        $this->command->info('Team test data created successfully!');
        $this->command->info('Test user: test@example.com / password');
        $this->command->info('Team member: member@example.com / password');
    }
}
