<?php

use App\Http\Controllers\InformUs\AppealController;
use App\Http\Controllers\InformUs\CommunityController;
use App\Http\Controllers\InformUs\CompanyController;
use App\Http\Controllers\InformUs\GroupController;
use App\Http\Controllers\InformUs\JobController;
use App\Http\Controllers\InformUs\PlaceController;
use App\Http\Controllers\InformUs\ProjectController;
use Illuminate\Support\Facades\Route;

Route::name('inform-us.')->group(function () {

    Route::get('/inform-us/company', [CompanyController::class, 'index'])->name('company');
    Route::post('/inform-us/company', [CompanyController::class, 'store']);

    Route::get('/inform-us/place', [PlaceController::class, 'index'])->name('place');
    Route::post('/inform-us/place', [PlaceController::class, 'store']);

    Route::get('/inform-us/group', [GroupController::class, 'index'])->name('group');
    Route::post('/inform-us/group', [GroupController::class, 'store']);

    Route::get('/inform-us/community', [CommunityController::class, 'index'])->name('community');
    Route::post('/inform-us/community', [CommunityController::class, 'store']);

    Route::get('/inform-us/job', [JobController::class, 'index'])->name('job');
    Route::post('/inform-us/job', [JobController::class, 'store']);

    Route::get('/inform-us/project', [ProjectController::class, 'index'])->name('project');
    Route::post('/inform-us/project', [ProjectController::class, 'store']);

    Route::get('/inform-us/appeal', [AppealController::class, 'index'])->name('appeal');
    Route::post('/inform-us/appeal', [AppealController::class, 'store']);
});
