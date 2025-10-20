<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyPhoneController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user() && $request->user()->hasVerifiedPhone()) {
            return redirect(RouteServiceProvider::HOME);
        }

        $smsService = new SmsService(true);

        $smsResult = $smsService->checkPhone($request->user()->phone);

        if ($smsResult) {
            if ($smsResult->status_code == 401) {
                
                $user = $request->user();

                $user->phone_verified_at = now();
                $user->phone_fore_verification = null;
                $user->check_id = null;

                $user->save();
                return redirect(RouteServiceProvider::HOME);
            }

            if ($smsResult->status_code == 402) {
                $request->user()->delete();
                return redirect()->route('register')->with('warning', 'Время для подтверждения вышло, пожалуйста повторите попытку');
            }
        }

        $phoneForeVerification = $request->user()->phone_fore_verification;

        $now_date = Carbon::parse(date('Y-m-d H:i:s'));    //время сейчас
        $old_date = Carbon::parse($request->user()->created_at); //дата с которой отчитываем 

        $endTimeForeVerification = $old_date->addMinutes(5);

        if ($now_date->getTimestamp() - Carbon::parse($request->user()->created_at)->getTimestamp() > 300) {
            $request->user()->delete();
            return redirect()->route('register')->with('warning', 'Время для подтверждения вышло, пожалуйста повторите попытку');
        }

        $timeForeVerification = $endTimeForeVerification->diffInSeconds($now_date);
        $timeForeVerification = gmdate('i:s', $timeForeVerification);

        return view('auth.phone-verify', [
            'phoneForeVerification' => $phoneForeVerification,
            'timeForeVerification' => $timeForeVerification
        ]);
    }
}
