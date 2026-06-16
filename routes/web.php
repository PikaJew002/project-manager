<?php

use App\Actions\AcceptInvite;
use App\Actions\CreateBucket;
use App\Actions\CreateSubtask;
use App\Actions\CreateProject;
use App\Actions\CreateTask;
use App\Actions\DeclineInvite;
use App\Actions\DeleteBucket;
use App\Actions\DeleteTask;
use App\Actions\InviteUser;
use App\Actions\Login;
use App\Actions\Logout;
use App\Actions\RegisterOrganization;
use App\Actions\ResetInvite;
use App\Actions\UpdateBucket;
use App\Actions\UpdateNotifications;
use App\Actions\UpdateSubtask;
use App\Actions\UpdateTask;
use App\Http\Pages\DashboardBoard;
use App\Http\Pages\DashboardGrid;
use App\Http\Pages\Organization;
use App\Http\Pages\ProjectBoard;
use App\Http\Pages\ProjectGrid;
use App\Http\Pages\RegisterFromInvite;
use App\Http\Pages\Settings\Account as AccountSettings;
use App\Http\Pages\Settings\Notifications as NotificationsSettings;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('marketing');
})->name('marketing');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::middleware('guest')->group(function () {
    // pages
    Route::get('/login', function () {
        return Inertia::render('Welcome');
    })->name('login');

    Route::get('/register', function () {
        return Inertia::render('RegisterOrganization');
    })->name('register-organization');

    Route::get('/register/invite/{token}', RegisterFromInvite::class)->name('register-invite');

    // api
    Route::post('/login', Login::class)->name('login-action');
    Route::post('/register', RegisterOrganization::class)->name('register');
    Route::post('/invite/accept', AcceptInvite::class)->name('invite-accept');
    Route::post('/invite/decline', DeclineInvite::class)->name('invite-decline');
    Route::post('/invite/reset', ResetInvite::class)->name('invite-reset');
});

Route::middleware('auth')->group(function () {
    // verify notice page
    Route::get('/email/verify', function (Request $request) {
        if ($request->user()->email_verified_at !== null) {
            session()->flash('inertia', ['status' => 'Your email address has already been verified.']);

            return response()->redirectToRoute('dashboard-grid');
        }

        return Inertia::render('VerifyNotice');
    })->name('verification.notice');


    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        session()->flash('inertia', ['status' => 'Your email address has been verified.']);

        return response()->redirectToRoute('dashboard-grid');
    })->middleware('signed')->name('verification.verify');

    // resend verification email
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        session()->flash('inertia', ['status' => 'Verification link sent!']);

        return response()->redirectToRoute('verification.notice');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::middleware('verified')->group(function () {
        // pages
        Route::get('/dashboard/grid', DashboardGrid::class)->name('dashboard-grid');
        Route::get('/dashboard/board', DashboardBoard::class)->name('dashboard-board');
        Route::get('/project/{id}/grid', ProjectGrid::class)->name('project-grid');
        Route::get('/project/{id}/board', ProjectBoard::class)->name('project-board');
        Route::get('/organization', Organization::class)->name('organization');

        Route::get('/settings/account', AccountSettings::class)->name('settings.account');
        Route::get('/settings/notifications', NotificationsSettings::class)->name('settings.notifications');

        // api
        Route::post('/logout', Logout::class)->name('logout');

        Route::post('/settings/notifications', UpdateNotifications::class)->name('update-notifications');

        Route::post('/task', CreateTask::class)->name('create-task');
        Route::put('/task/{id}', UpdateTask::class)->name('update-task');
        Route::delete('/task/{id}', DeleteTask::class)->name('delete-task');

        Route::post('/subtask', CreateSubtask::class)->name('create-subtask');
        Route::put('/subtask/{id}', UpdateSubtask::class)->name('update-subtask');

        Route::post('/bucket', CreateBucket::class)->name('create-bucket');
        Route::put('/bucket/{id}', UpdateBucket::class)->name('update-bucket');
        Route::delete('/bucket/{id}', DeleteBucket::class)->name('delete-bucket');

        Route::post('/project', CreateProject::class)->name('create-project');

        Route::post('/invite', InviteUser::class)->name('invite-user');

        Route::redirect('/dashboard', '/dashboard/grid');
    });
});
