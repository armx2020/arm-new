<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\SmsService;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create(Request $request)
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $smsService = new SmsService(true);

        $request->validate([
            'firstname' => ['required', 'string', 'max:32'],
            'phone' => ['required', 'max:32', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults(), Rules\Password::min(6)],
        ]);

        $smsResult = $smsService->checkPhone($request->phone);

        if (!$smsResult) {
            return redirect()->route('register')->with('error',  "Запрос не выполнился. Попробуйте позже");
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'phone' => $request->phone,
            'password' => Hash::make($request->session()->get('password')),
            'phone_fore_verification' => $smsResult->call_phone_pretty,
            'check_id' => $smsResult->check_id
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
