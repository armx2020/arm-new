<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DbSwitchController extends Controller
{
    public function switch(Request $request)
    {
        // Работает только в Replit
        if (!$this->isReplit()) {
            abort(404);
        }

        $mode = $request->input('mode', 'demo');
        
        if (!in_array($mode, ['demo', 'production'])) {
            return redirect()->back();
        }

        $request->session()->put('db_mode', $mode);
        
        $message = $mode === 'demo' 
            ? '🚀 Демо-режим: PostgreSQL (США, быстро)' 
            : '🔴 Боевой режим: MySQL (Россия, медленно)';
        
        return redirect()->back()->with('success', $message);
    }
    
    private function isReplit(): bool
    {
        return !empty(getenv('REPLIT_DEV_DOMAIN')) || !empty(getenv('REPLIT_DOMAINS'));
    }
}
