<?php

use App\Actions\AcceptInvite;
use App\Actions\CreateBucket;
use App\Actions\CreateInvite;
use App\Actions\CreatePersonalTask;
use App\Actions\CreateProject;
use App\Actions\DeclineInvite;
use App\Actions\DeleteBucket;
use App\Actions\DeleteTask;
use App\Actions\Login;
use App\Actions\Logout;
use App\Actions\RegisterOrganization as RegisterOrganizationAction;
use App\Actions\ResetInvite;
use App\Actions\UpdateBucket;
use App\Actions\UpdatePersonalTask;
use App\Http\Pages\DashboardBoard;
use App\Http\Pages\DashboardGrid;
use App\Http\Pages\Organization;
use App\Http\Pages\ProjectBoard;
use App\Http\Pages\ProjectGrid;
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
    Route::post('/invite/accept', AcceptInvite::class)->name('invite-accept');
    Route::post('/invite/decline', DeclineInvite::class)->name('invite-decline');
    Route::post('/invite/reset', ResetInvite::class)->name('invite-reset');
});

Route::middleware('auth')->group(function () {
    // pages
    Route::get('/dashboard/grid', DashboardGrid::class)->name('dashboard-grid');
    Route::get('/dashboard/board', DashboardBoard::class)->name('dashboard-board');
    Route::get('/project/{id}/grid', ProjectGrid::class)->name('project-grid');
    Route::get('/project/{id}/board', ProjectBoard::class)->name('project-board');
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

    Route::post('/invite', CreateInvite::class)->name('create-invite');

    Route::redirect('/dashboard', '/dashboard/grid');
});
