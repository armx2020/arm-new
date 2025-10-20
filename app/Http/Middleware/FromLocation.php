<?php

namespace App\Http\Middleware;

use App\Models\Region;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stevebauman\Location\Facades\Location;

class FromLocation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('regionTranslit')) {

            // Упрощенная версия для Replit - определение локации отключено
            $regionName = 'russia';

            $region = Region::find(1); // Россия по умолчанию

            if ($region) {
                $request->session()->put('regionName', $region->name);
                $request->session()->put('regionTranslit', $region->transcription);
            } else {
                $request->session()->put('regionName', 'не выбрано');
                $request->session()->put('regionTranslit', 'russia');
            }
        }

        return $next($request);
    }
}
