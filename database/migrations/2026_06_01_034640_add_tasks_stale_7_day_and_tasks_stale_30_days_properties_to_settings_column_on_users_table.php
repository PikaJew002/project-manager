<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::query()->cursor()->each(function (User $user) {
            $user->settings['notifications']['tasks_stale_7_days'] = false;
            $user->settings['notifications']['tasks_stale_30_days'] = false;
            $user->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::query()->cursor()->each(function (User $user) {
            unset($user->settings['notifications']['tasks_stale_7_days']);
            unset($user->settings['notifications']['tasks_stale_30_days']);
            $user->save();
        });
    }
};
