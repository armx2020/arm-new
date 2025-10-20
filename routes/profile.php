<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Profile\MyCommunityController;
use App\Http\Controllers\Profile\MyProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profile\MyCompanyController;
use App\Http\Controllers\Profile\MyGroupController;
use App\Http\Controllers\Profile\MyJobController;
use App\Http\Controllers\Profile\MyMessengerController;
use App\Http\Controllers\Profile\MyOfferController;
use App\Http\Controllers\Profile\MyPlacesController;

Route::middleware(['auth', 'verificate_phone'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/questions', [DashboardController::class, 'questions'])->name('questions');

    // Profile
    Route::get('/profile', [MyProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [MyProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [MyProfileController::class, 'destroy'])->name('profile.destroy');

    
    //Messenger
    // Route::get('/messenger', [MyMessengerController::class, 'index'])->name('messenger');  TODO - доделать мессенджер
    // Route::post('/messenger', [MyMessengerController::class, 'store']);

    Route::resources([
        'mygroups'      =>  MyGroupController::class,
        'mycompanies'   =>  MyCompanyController::class,
        'myoffers'      =>  MyOfferController::class,
        'myplaces'      =>  MyPlacesController::class,
        'mycommunities' =>  MyCommunityController::class,
        'myjobs'        =>  MyJobController::class
    ]);
});