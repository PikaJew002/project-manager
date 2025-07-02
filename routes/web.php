<?php

use App\Actions\AcceptOrganizationInvitation;
use App\Actions\CreateBucket;
use App\Actions\CreateOrganizationInvitation;
use App\Actions\CreatePersonalTask;
use App\Actions\CreateProject;
use App\Actions\DeclineOrganizationInvitation;
use App\Actions\DeleteBucket;
use App\Actions\DeleteTask;
use App\Actions\Login;
use App\Actions\Logout;
use App\Actions\RegisterOrganization as RegisterOrganizationAction;
use App\Actions\ResetOrganizationInvitation;
use App\Actions\UpdateBucket;
use App\Actions\UpdatePersonalTask;
use App\Http\Pages\DashboardBoard;
use App\Http\Pages\DashboardGrid;
use App\Http\Pages\Organization;
use App\Http\Pages\ProjectBoard;
use App\Http\Pages\ProjectGrid;
use App\Http\Pages\ProjectManage;
use App\Http\Pages\RegisterFromInvite;
use App\Http\Pages\RegisterOrganization;
use App\Http\Pages\Welcome;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // page
    Route::get('/', Welcome::class)->name('welcome');
    Route::get('/register', RegisterOrganization::class)->name('register-organization');
    Route::get('/register/invite/{token}', RegisterFromInvite::class)->name('register-invite');

    // api
    Route::post('/login', Login::class)->name('login');
    Route::post('/register', RegisterOrganizationAction::class)->name('register');
    Route::post('/invite/accept', AcceptOrganizationInvitation::class)->name('invite-accept');
    Route::post('/invite/decline', DeclineOrganizationInvitation::class)->name('invite-decline');
    Route::post('/invite/reset', ResetOrganizationInvitation::class)->name('invite-reset');
});

Route::middleware('auth')->group(function () {
    // pages
    Route::get('/dashboard/grid', DashboardGrid::class)->name('dashboard-grid');
    Route::get('/dashboard/board', DashboardBoard::class)->name('dashboard-board');
    Route::get('/project/{id}/grid', ProjectGrid::class)->name('project-grid');
    Route::get('/project/{id}/board', ProjectBoard::class)->name('project-board');
    Route::get('/project/{id}/manage', ProjectManage::class)->name('project-manage');
    Route::get('/organization', Organization::class)->name('organization');

    // api
    Route::post('/logout', Logout::class)->name('logout');

    Route::post('/task', CreatePersonalTask::class)->name('create-task');
    Route::put('/task/{id}', UpdatePersonalTask::class)->name('update-task');
    Route::delete('/task/{id}', DeleteTask::class)->name('delete-task');

    Route::post('/bucket', CreateBucket::class)->name('create-bucket');
    Route::put('/bucket/{id}', UpdateBucket::class)->name('update-bucket');
    Route::delete('/bucket/{id}', DeleteBucket::class)->name('delete-bucket');

    Route::post('/project', CreateProject::class)->name('create-project');

    Route::post('/invite', CreateOrganizationInvitation::class)->name('create-invite');

    Route::redirect('/dashboard', '/dashboard/grid');
});


$authMiddleware = config('jetstream.guard')
    ? 'auth:' . config('jetstream.guard')
    : 'auth';

$authSessionMiddleware = config('jetstream.auth_session', false)
    ? config('jetstream.auth_session')
    : null;

Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware]))], function () {
    // User & Profile...
    Route::get('/user/profile', [UserProfileController::class, 'show'])
        ->name('profile.show');

    Route::delete('/user/other-browser-sessions', [OtherBrowserSessionsController::class, 'destroy'])
        ->name('other-browser-sessions.destroy');

    Route::delete('/user/profile-photo', [ProfilePhotoController::class, 'destroy'])
        ->name('current-user-photo.destroy');

    // Route::delete('/user', [CurrentUserController::class, 'destroy'])
    //     ->name('current-user.destroy');

    Route::group(['middleware' => 'verified'], function () {
        Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
        Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
        Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
        Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
        Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
        Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');
        Route::post('/teams/{team}/members', [TeamMemberController::class, 'store'])->name('team-members.store');
        Route::put('/teams/{team}/members/{user}', [TeamMemberController::class, 'update'])->name('team-members.update');
        Route::delete('/teams/{team}/members/{user}', [TeamMemberController::class, 'destroy'])->name('team-members.destroy');

        Route::get('/team-invitations/{invitation}', [TeamInvitationController::class, 'accept'])
            ->middleware(['signed'])
            ->name('team-invitations.accept');

        Route::delete('/team-invitations/{invitation}', [TeamInvitationController::class, 'destroy'])
            ->name('team-invitations.destroy');
    });
});
