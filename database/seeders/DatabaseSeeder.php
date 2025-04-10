<?php

namespace Database\Seeders;

use App\Models\Bucket;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // for all tests
        $org = Organization::factory()->create([
            'name' => 'KCTCS Marketing',
        ]);
        // for most tests
        $admin = User::factory()->for($org)->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => true,
        ]);

        $adminPersonalProject = Project::factory()->for($org)->create([
            'administered_by' => $admin->id,
            'name' => 'Private Tasks',
            'initials' => 'PT',
            'is_personal' => true,
        ]);
        // for some tests
        $users = User::factory()
            ->count(5)
            ->for($org)
            ->has(
                Project::factory()->for($org)->state([
                    'name' => 'Private Tasks',
                    'initials' => 'PT',
                    'is_personal' => true,
                ]),
                'administering'
            )
            ->create();

        $project = Project::factory()
            ->for($org)
            ->for($admin, 'administeredBy')
            ->hasAttached($users[4], ['accepted_at' => now()])
            ->create();

        Bucket::factory()->for($project)->for($users[0], 'createdBy')->create();

        Project::factory()->for($org)->for($admin, 'administeredBy')->hasAttached($users[1], ['accepted_at' => now()])->create();
        Project::factory()->for($org)->for($users[0], 'administeredBy')->hasAttached($users[2], ['accepted_at' => now()])->create();

        Task::factory()->for($admin, 'createdBy')
            ->hasAttached($admin, ['assigned_by' => $admin->id])
            ->hasAttached($adminPersonalProject)
            ->count(3)->create();
    }
}
