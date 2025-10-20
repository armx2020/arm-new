<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class VerificatePhone
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->hasVerifiedPhone()) {
            return redirect()->route('phone.verify');
        }

        return $next($request);
    }
}
