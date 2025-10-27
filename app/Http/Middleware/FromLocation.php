<?php

namespace App\Http\Middleware;

use App\Models\Region;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class FromLocation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('regionTranslit')) {
            // DEMO режим или продакшн - устанавливаем быстрые значения
            $request->session()->put('regionName', 'Россия');
            $request->session()->put('regionTranslit', 'russia');
            $request->session()->put('lat', 55.7558);
            $request->session()->put('lon', 37.6176);
            $request->session()->put('isRussia', true);
        }

        return $next($request);
    }
}
