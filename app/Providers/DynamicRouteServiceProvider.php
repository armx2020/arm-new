<?php

namespace App\Providers;

use App\Http\Controllers\Pages\DinamicRouteController;
use App\Http\Controllers\Pages\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class DynamicRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $items = Cache::get('dynamic_routes', []);
        $regularString = '^(?!';

        foreach ($items as $plural => $singular) {
            if ($regularString !== '^(?!') {
                $regularString = $regularString . '$|' . $plural . '$|' . $singular;
            } else {
                $regularString = $regularString . $plural . '$|' . $singular;
            }
        }

        $regularString = $regularString . '$).*';

        foreach ($items as $plural => $singular) {
            Route::middleware('web')->group(function () use ($plural, $singular) {
                Route::get("/{$plural}/{category?}/{subCategory?}", [DinamicRouteController::class, $plural])->name("{$plural}.index");
                Route::get("/{regionTranslit}/{$plural}/{category?}/{subCategory?}", [DinamicRouteController::class, "$plural-region"])->name("{$plural}.region");
                Route::get("/{$singular}/{idOrTranscript}", [DinamicRouteController::class, "$plural-show"])->name("$singular.show");
            });


            if ($regularString !== '^(?!') {
                $regularString = $regularString . '$|' . $plural . '$|' . $singular;
            } else {
                $regularString = $regularString . $plural . '$|' . $singular;
            }
        }

        Route::middleware('web')->get('/{regionTranslit?}', [HomeController::class, 'home'])->where('regionTranslit', $regularString)->name('home');
    }
}
