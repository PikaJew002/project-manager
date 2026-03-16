<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use App\Notifications\TaskDue;
use DateTime;
use DateTimeZone;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\DB;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            HandleInertiaRequests::class,
        ]);
        $middleware->redirectGuestsTo(fn() => route('welcome'));
        $middleware->redirectUsersTo(fn () => route('dashboard-grid'));
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('model:prune')->daily();
        $schedule->call(function () {
            $users = User::with(['tasks' => function (BelongsToMany $query) {
                $query->whereNull('completed_at')->whereNotNull('due_at')->whereBetween('due_at', [now()->subHours(8)->setMinutes(0)->setSeconds(0), now()->addHours(16)->setMinutes(0)->setSeconds(0)])->wherePivotNull('notified_at');
            }])->get()->filter(function (User $user) {
                $tz = new DateTimeZone($user->timezone ?? config('app.timezone'));
                $now = new DateTime('now', $tz);

                if ((int) $now->format('H') === 8 && (int) $now->format('i') === 0) {
                    return true;
                }
                return false;
            })->filter(function (User $user) {
                return $user->tasks->isNotEmpty();
            });

            foreach ($users as $user) {
                /** @var User $user */
                foreach ($user->tasks as $task) {
                    $user->notify(new TaskDue($task));
                    DB::table('task_user')->where('task_id', $task->id)->where('user_id', $user->id)->update(['notified_at' => now(), 'updated_at' => now()]);
                }
            }
        })->hourly();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
