<?php

namespace App\Http\Controllers\Admin\Telegram;

use App\Http\Controllers\Controller;
use App\Models\TelegramUser;

class TelegramUserController extends Controller
{
    public function index()
    {
        return view('admin.telegram.telegram-user.index');
    }

    public function destroy(TelegramUser $telegram_user)
    {
        $telegram_user->delete();

        return redirect()->route('admin.telegram_user.index')->with('success', 'Телеграмм-пользователь удален');
    }
}
