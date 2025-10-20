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
            try {
                // Пробуем получить регион из БД
                $region = DB::table('regions')->where('id', 1)->first();
                
                if ($region) {
                    $request->session()->put('regionName', $region->name ?? 'Россия');
                    $request->session()->put('regionTranslit', $region->transcription ?? 'russia');
                } else {
                    // Если регион не найден, используем значения по умолчанию
                    $request->session()->put('regionName', 'Россия');
                    $request->session()->put('regionTranslit', 'russia');
                }
            } catch (\Exception $e) {
                // В случае ошибки БД, устанавливаем безопасные значения по умолчанию
                $request->session()->put('regionName', 'Россия');
                $request->session()->put('regionTranslit', 'russia');
            }
        }

        return $next($request);
    }
}
