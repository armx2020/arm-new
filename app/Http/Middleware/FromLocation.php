<?php

namespace App\Http\Middleware;

use App\Models\Region;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FromLocation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('regionTranslit')) {

            // Временно отключено автоопределение геолокации
            // Устанавливаем Россию по умолчанию
            
            $region = Region::find(1); // ID=1 обычно Россия

            if ($region) {
                $request->session()->put('regionName', $region->name ?? 'Россия');
                $request->session()->put('regionTranslit', $region->transcription ?? 'russia');
            } else {
                // Фоллбэк если регион с ID=1 не найден
                $request->session()->put('regionName', 'Россия');
                $request->session()->put('regionTranslit', 'russia');
            }
        }

        return $next($request);
    }
}
