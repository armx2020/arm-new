<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sum =  (Auth::user()->city !== 1 ? 10 : 0) +
            (Auth::user()->image ? 10 : 0) +
            (Auth::user()->whatsapp ? 5 : 0) +
            (Auth::user()->instagram ? 5 : 0) +
            (Auth::user()->vkontakte ? 5 : 0) +
            (Auth::user()->telegram ? 5 : 0);

        $fullness = (round(($sum / 45) * 100));

        return view('dashboard', [
            'fullness' => $fullness,
        ]);
    }

    public function questions(Request $request)
    {
        return view('profile.pages.questions');
    }
}
