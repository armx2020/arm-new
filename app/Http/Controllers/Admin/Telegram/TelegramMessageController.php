<?php

namespace App\Http\Controllers\Admin\Telegram;

use App\Http\Controllers\Controller;
use App\Models\TelegramMessage;

class TelegramMessageController extends Controller
{
    public function index()
    {
        return view('admin.telegram.telegram-message.index');
    }

    public function destroy(TelegramMessage $telegram_message)
    {
        $telegram_message->delete();

        return redirect()->route('admin.telegram_message.index')->with('success', 'Телеграмм-сообщение удален');
    }
}
