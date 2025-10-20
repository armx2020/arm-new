<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\EntityController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/cities', [CityController::class, 'get'])->name('get-city');
Route::get('/users', [UserController::class, 'get'])->name('get-user');
Route::get('/moderators', [UserController::class, 'get_moderator'])->name('get-moderator');
Route::get('/entities', [EntityController::class, 'get'])->name('get-entity');
