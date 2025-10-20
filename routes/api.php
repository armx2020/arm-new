<?php

use App\Http\Controllers\Api\CategoryForOfferController;
use App\Http\Controllers\Api\EntityForHomePageController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\MapController;
use App\Http\Controllers\Api\WebHookController;
use Illuminate\Support\Facades\Route;

Route::get('/cities', [CityController::class, 'get'])->name('cities');
Route::post('/actions', [CategoryForOfferController::class, 'get'])->name('actions');
Route::get('/entities', [EntityForHomePageController::class, 'get'])->name('entities');
Route::post('/web-hooks/sms-ru', [WebHookController::class, 'store'])->name('web-hooks');
Route::get('/nearby-entities', [MapController::class, 'nearbyEntities'])->name('nearby-entities');