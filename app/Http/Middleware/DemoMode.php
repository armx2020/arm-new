<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Только для не-продакшн окружения (Replit)
        if (config('app.env') !== 'production') {
            // Устанавливаем DEMO режим по умолчанию, если не задан
            if (!session()->has('demo_mode')) {
                session(['demo_mode' => true]);
            }
        } else {
            // На продакшене DEMO режим всегда выключен
            session(['demo_mode' => false]);
        }

        return $next($request);
    }
}
