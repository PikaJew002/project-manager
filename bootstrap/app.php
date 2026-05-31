<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use App\Notifications\TaskDue;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpFoundation\Response;

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
        // Notify users of tasks that are due today at precisely 8:00 AM in their timezone
        $schedule->call(function () {
            $users = User::with(['tasks' => function (BelongsToMany $query) {
                $query->whereNull('completed_at')
                    ->whereNotNull('due_at')
                    ->whereBetween('due_at', [
                        now()->subHours(8)->setMinutes(0)->setSeconds(0),
                        now()->addHours(16)->setMinutes(0)->setSeconds(0),
                    ])->wherePivotNull('notified_at');
            }])->where('settings->notifications->daily_tasks_due', true)->get()->filter(function (User $user) {
                return $user->is8AMInTimezone();
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
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if ($response->getStatusCode() === 419) {
                return back()->withInput(['message' => 'Session expired. Please login again.'])->redirectToRoute('welcome');
            }

            return $response;
        });
        Integration::handles($exceptions);
    })->create();
