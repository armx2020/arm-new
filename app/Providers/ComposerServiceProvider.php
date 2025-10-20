<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ComposerServiceProvider extends ServiceProvider
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
        View::composer(['layouts.nav', 'layouts.footer', 'pages.entity.*', 'inform-us.*'], function ($view) {
            $request = app(Request::class);
            $region = $request->session()->get('regionTranslit');
            $regionName = $request->session()->get('regionName');
            $regions = Cache::get('regions', []);
            $countries = Cache::get('countries', []);

            if (empty($this->regions) || empty($this->countries)) {
                Artisan::call('cache-regions');
            }

            $menuItems = collect(config('menu.main'))->map(function ($item) use ($region, $regionName, $request) {
                $isRegional = ($regionName && $regionName !== 'Россия');

                return [
                    'title' => $item['title'],
                    'url' => route(
                        $isRegional ? $item['routes']['region'] : $item['routes']['index'],
                        $isRegional ? ['regionTranslit' => $region] : []
                    ),
                    'is_active' => $this->isMenuItemActive($item, $request)
                ];
            });

            $view->with([
                'headerMenu' => $menuItems,
                'regionName' => $regionName,
                'region'    => $region,
                'regions' => $regions,
                'countries' => $countries
            ]);
        });

        View::composer('pages.entity.index', function ($view) {
            $request = app(Request::class);
            $lat = $request->session()->get('lat');
            $lon = $request->session()->get('lon');

            $view->with([
                'lat'   => $lat,
                'lon' => $lon,
            ]);
        });

        View::composer('admin.layouts.navigation', function ($view) {
            $view->with([
                'menu'   => config('menu.admin'),
            ]);
        });
    }

    protected function isMenuItemActive(array $item, Request $request): bool
    {
        $currentRoute = $request->route()->getName();
        return in_array($currentRoute, [
            $item['routes']['index'],
            $item['routes']['region']
        ]);
    }
}
