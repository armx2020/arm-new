<?php

use App\Http\Controllers\Pages\EntityController;
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\ProfileController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/inform-us.php';

Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/condition-of-use', [HomeController::class, 'conditionOfUse'])->name('condition-of-use');

Route::get('/edit/{idOrTranscript}', [EntityController::class, 'edit'])->name('entity.edit');
Route::patch('/{idOrTranscript}', [EntityController::class, 'update'])->name('entity.update');

Route::get('/photo/{idOrTranscript}', [EntityController::class, 'editPhoto'])->name('entity.photo.edit');
Route::patch('/photo/{idOrTranscript}', [EntityController::class, 'updatePhoto'])->name('entity.photo.update');

Route::get('/user/{id}', [ProfileController::class, 'show'])->name('user.show');
