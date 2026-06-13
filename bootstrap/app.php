<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use App\Notifications\TasksDue;
use App\Notifications\TasksStale;
use App\Mail\ShabbatZoomLink;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
                    ]);
            }])->where('settings->notifications->daily_tasks_due', true)->get()->filter(function (User $user) {
                return $user->is8AMInTimezone();
            })->filter(function (User $user) {
                return $user->tasks->isNotEmpty();
            });

            foreach ($users as $user) {
                /** @var User $user */
                $user->notify(new TasksDue($user->tasks, 'Today'));
            }
        })->hourly();

        // Notify users of tasks that are due this week at precisely Monday 8:00 AM in their timezone
        $schedule->call(function () {
            $users = User::with([
                'tasks' => function (BelongsToMany $query) {
                    $query->whereNull('completed_at')
                        ->whereNotNull('due_at')
                        ->whereBetween('due_at', [
                            now()->subHours(8)->setMinutes(0)->setSeconds(0),
                            now()->addDays(4)->addHours(16)->setMinutes(0)->setSeconds(0),
                        ]);
                }
            ])->where('settings->notifications->weekly_tasks_due', true)->get()->filter(function (User $user) {
                return $user->isMondayInTimezone() && $user->is8AMInTimezone();
            })->filter(function (User $user) {
                return $user->tasks->isNotEmpty();
            });

            foreach ($users as $user) {
                /** @var User $user */
                $user->notify(new TasksDue($user->tasks, 'This Week'));
            }
        })->hourly();

        // Notify users of tasks that have had no activity for 7 days at precisely 8:00 AM in their timezone
        $schedule->call(function () {
            $users = User::with(['tasks' => function (BelongsToMany $query) {
                $query->whereNull('completed_at')
                    ->whereNull('due_at')
                    ->whereBetween('tasks.updated_at', [
                        now()->subDays(7)->subHours(8)->setMinutes(0)->setSeconds(0),
                        now()->subDays(7)->addHours(16)->setMinutes(0)->setSeconds(0),
                    ]);
            }])->where('settings->notifications->tasks_stale_7_days', true)->get()->filter(function (User $user) {
                return $user->is8AMInTimezone();
            })->filter(function (User $user) {
                return $user->tasks->isNotEmpty();
            });

            foreach ($users as $user) {
                /** @var User $user */
                $user->notify(new TasksStale($user->tasks, 7));
            }
        })->hourly();

        // Notify users of tasks that have had no activity for 30 days at precisely 8:00 AM in their timezone
        $schedule->call(function () {
            $users = User::with([
                'tasks' => function (BelongsToMany $query) {
                    $query->whereNull('completed_at')
                        ->whereNull('due_at')
                        ->whereBetween('tasks.updated_at', [
                            now()->subDays(30)->subHours(8)->setMinutes(0)->setSeconds(0),
                            now()->subDays(30)->addHours(16)->setMinutes(0)->setSeconds(0),
                        ]);
                }
            ])->where('settings->notifications->tasks_stale_30_days', true)->get()->filter(function (User $user) {
                return $user->is8AMInTimezone();
            })->filter(function (User $user) {
                return $user->tasks->isNotEmpty();
            });

            foreach ($users as $user) {
                /** @var User $user */
                $user->notify(new TasksStale($user->tasks, 30));
            }
        })->hourly();

        $schedule->call(function () {
            Mail::mailer('smtp')->send(new ShabbatZoomLink());
        })->weeklyOn(4, '17:00')->timezone('America/New_York'); // Thursday at 5:00 PM EST
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if ($response->getStatusCode() === 419) {
                session()->flash('inertia', ['status' => 'Session expired. Please login again.']);
                return response()->redirectToRoute('welcome');
            }

            return $response;
        });
        Integration::handles($exceptions);
    })->create();
