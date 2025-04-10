<?php

use App\Actions\CreateBucket;
use App\Actions\CreatePersonalTask;
use App\Actions\DeleteBucket;
use App\Actions\DeleteTask;
use App\Actions\Login;
use App\Actions\Logout;
use App\Actions\UpdateBucket;
use App\Actions\UpdatePersonalTask;
use App\Http\Controllers\ShowDashboardBoard;
use App\Http\Controllers\ShowDashboardGrid;
use App\Http\Controllers\ShowLogin;
use App\Http\Controllers\ShowProjectBoard;
use App\Http\Controllers\ShowProjectGrid;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // page
    Route::get('/', ShowLogin::class)->name('welcome');

    // api
    Route::post('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    // pages
    Route::get('/dashboard/grid', ShowDashboardGrid::class)->name('dashboard-grid');
    Route::get('/dashboard/board', ShowDashboardBoard::class)->name('dashboard-board');
    Route::get('/project/{id}/grid', ShowProjectGrid::class)->name('project-grid');
    Route::get('/project/{id}/board', ShowProjectBoard::class)->name('project-board');

    // api
    Route::post('/logout', Logout::class)->name('logout');

    Route::post('/task', CreatePersonalTask::class)->name('create-task');
    Route::put('/task/{id}', UpdatePersonalTask::class)->name('update-task');
    Route::delete('/task/{id}', DeleteTask::class)->name('delete-task');

    Route::post('/bucket', CreateBucket::class)->name('create-bucket');
    Route::put('/bucket/{id}', UpdateBucket::class)->name('update-bucket');
    Route::delete('/bucket/{id}', DeleteBucket::class)->name('delete-bucket');
});
