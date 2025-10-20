<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordResetPhoneController extends Controller
{
    public function create(Request $request): View
    {
        $request->session()->forget('reset_phone');
        $request->session()->forget('check_id_phone');
        $request->session()->forget('call_phone_pretty');
        $request->session()->forget('time_to_reset_phone');

        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => [
                'required',
                'max:32',
                function ($attribute, $value, $fail) {
                    if (!DB::table('users')->where('phone', $value)->exists()) {
                        $fail('Данный номер не зарегистрирован.');
                    }
                },
            ],
        ]);

        $user = User::where('phone', $request->phone)->exists();

        if (!$user) {
            return redirect()->route('forgot-password')->with('error',  "Мы не нашли пользователей с такими данными.");
        }

        $smsService = new SmsService(true);

        $smsResult = $smsService->checkPhone($request->phone);

        if (!$smsResult) {
            return redirect()->route('forgot-password')->with('error',  "Запрос не выполнился. Попробуйте позже");
        }

        if ($smsResult) {
            if ($smsResult->status_code == 100) {
                $request->session()->put('reset_phone', $request->phone);
                $request->session()->put('check_id_phone', $smsResult->check_id);
                $request->session()->put('call_phone_pretty', $smsResult->call_phone_pretty);
                $request->session()->put('time_to_reset_phone', Carbon::parse(date('Y-m-d H:i:s')));

                return redirect()->route('confirm-phone');
            }

            if ($smsResult->status_code == 202) {
                return redirect()->route('forgot-password')->with('warning', 'Номер указан неверно, пожалуйста повторите попытку');
            }
        }

        return redirect()->route('forgot-password')->with('error',  "Запрос не выполнился. Попробуйте позже");
    }

    public function confirmPhone(Request $request)
    {
        if (!$request->session()->get('check_id_phone')) {
            return redirect()->route('forgot-password');
        }

        $smsService = new SmsService(true);

        $smsResult = $smsService->checkId($request->session()->get('check_id_phone'));

        if ($smsResult) {
            if ($smsResult->check_status == 401) {

                $user = User::where('phone', $request->session()->get('reset_phone'))->first();

                if (!$user) {
                    $request->session()->forget('reset_phone');
                    $request->session()->forget('check_id_phone');
                    $request->session()->forget('call_phone_pretty');
                    $request->session()->forget('time_to_reset_phone');
                    return redirect()->route('forgot-password')->with('error',  "Мы не нашли пользователей с такими данными.");
                }

                $user->phone_verified_at = now();
                $user->save();

                Auth::login($user);

                return redirect()->route('new-password');
            }

            if ($smsResult->check_status == 402) {
                $request->session()->forget('reset_phone');
                $request->session()->forget('check_id_phone');
                $request->session()->forget('call_phone_pretty');
                $request->session()->forget('time_to_reset_phone');

                return redirect()->route('forgot-password')->with('warning', 'Время для подтверждения вышло, пожалуйста повторите попытку');
            }
        }

        $phoneForeVerification = $request->session()->get('call_phone_pretty');

        $now_date = Carbon::parse(date('Y-m-d H:i:s'));    //время сейчас
        $old_date = Carbon::parse($request->session()->get('time_to_reset_phone')); //дата с которой отчитываем 

        $endTimeForeVerification = $old_date->addMinutes(5);

        if ($now_date->getTimestamp() - Carbon::parse($old_date)->getTimestamp() > 300) {
            $request->session()->forget('reset_phone');
            $request->session()->forget('check_id_phone');
            $request->session()->forget('call_phone_pretty');
            $request->session()->forget('time_to_reset_phone');

            return redirect()->route('forgot-password')->with('warning', 'Время для подтверждения вышло, пожалуйста повторите попытку');
        }

        $timeForeVerification = $endTimeForeVerification->diffInSeconds($now_date);
        $timeForeVerification = gmdate('i:s', $timeForeVerification);

        return view('auth.phone-verify', [
            'phoneForeVerification' => $phoneForeVerification,
            'timeForeVerification' => $timeForeVerification
        ]);
    }


    public function newPassword(Request $request)
    {
        if ($request->user() && !$request->user()->hasVerifiedPhone()) {
            return redirect(RouteServiceProvider::HOME);
        }

        if (!$request->session()->get('check_id_phone')) {
            return redirect(RouteServiceProvider::HOME);
        }

        $request->session()->forget('reset_phone');
        $request->session()->forget('check_id_phone');
        $request->session()->forget('call_phone_pretty');
        $request->session()->forget('time_to_reset_phone');

        return view('auth.new-password');
    }

    public function newPasswordStore(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect(RouteServiceProvider::HOME);
    }
}
