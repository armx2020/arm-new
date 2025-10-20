<?php

use App\Http\Controllers\Admin\AppealController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryEntityController;
use App\Http\Controllers\Admin\EntityController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\Telegram\TelegramGroupController;
use App\Http\Controllers\Admin\Telegram\TelegramMessageController;
use App\Http\Controllers\Admin\Telegram\TelegramUserController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UserController;

Route::name('admin.')->prefix('admin')->group(function () {

    Route::middleware(['role:super-admin|moderator'])->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('entity', EntityController::class)->except([
            'show'
        ]);

        Route::group(['middleware' => ['role:super-admin']], function () {
            Route::resource('user', UserController::class)->except([
                'show'
            ]);
            Route::put('user/update-password/{user}', [UserController::class, 'updateUserPassword'])->name('user.update-password');
            Route::resource('category', CategoryController::class)->except([
                'show'
            ]);
            Route::resource('type', TypeController::class)->except([
                'show'
            ]);
            Route::resource('image', ImageController::class)->except([
                'show',
                'create',
                'store',
                'update'
            ]);

            Route::resource('offer', OfferController::class)->except([
                'show'
            ]);

            Route::resource('appeal', AppealController::class)->except([
                'show',
                'create',
                'store'
            ]);

            Route::get('category-entity', [CategoryEntityController::class, 'index'])->name('category-entity.index');

            // telegram
            Route::resource('telegram_group', TelegramGroupController::class)->except([
                'show',
                'edit',
                'update'
            ]);

            Route::resource('telegram_user', TelegramUserController::class)->except([
                'show',
                'create',
                'store',
                'edit',
                'update'
            ]);

            Route::resource('telegram_message', TelegramMessageController::class)->except([
                'show',
                'create',
                'store',
                'edit',
                'update'
            ]);


            // report
            Route::get('entity/report', [EntityController::class, 'report'])->name('entity.report');
            Route::get('entity/report-two', [EntityController::class, 'reportTwo'])->name('entity.report-two');
            Route::get('entity/report-double', [EntityController::class, 'reportDouble'])->name('entity.report-double');

        });
    });

    require __DIR__ . '/admin-api.php';
});
