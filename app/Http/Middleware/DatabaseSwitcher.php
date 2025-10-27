<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class DatabaseSwitcher
{
    public function handle(Request $request, Closure $next): Response
    {
        // Работает только в Replit
        if ($this->isReplit()) {
            $mode = $request->session()->get('db_mode', 'demo');
            
            // demo = PostgreSQL (быстро, демо-данные)
            // production = MySQL (медленно, боевые данные из России)
            $connection = $mode === 'production' ? 'mysql_dev' : 'pgsql';
            
            Config::set('database.default', $connection);
        }
        
        return $next($request);
    }
    
    private function isReplit(): bool
    {
        return !empty(getenv('REPLIT_DEV_DOMAIN')) || !empty(getenv('REPLIT_DOMAINS'));
    }
}
