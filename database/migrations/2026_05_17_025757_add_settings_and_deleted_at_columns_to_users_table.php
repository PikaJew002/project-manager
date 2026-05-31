<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('settings')->nullable()->after('email');
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
        });

        DB::table('users')->update([
            'settings' => json_encode([
                'notifications' => [
                    'daily_tasks_due' => false,
                    'weekly_tasks_due' => false,
                    'tasks_assigned' => false,
                    'tasks_updated' => false,
                ],
            ]),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('settings');
            $table->dropColumn('deleted_at');
        });
    }
};
