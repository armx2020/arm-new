<?php

namespace App\Http\Controllers\Admin\Telegram;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Telegram\TelegramGroupRequest;
use App\Models\TelegramGroup;
use Illuminate\Support\Facades\Http;

class TelegramGroupController extends Controller
{
    public function index()
    {
        return view('admin.telegram.telegram-group.index');
    }

    public function create()
    {
        return view('admin.telegram.telegram-group.create');
    }

    public function store(TelegramGroupRequest $request)
    {
        $newGroup = $this->checkGroup($request->username);

        if ($newGroup) {
            $telegram_group = TelegramGroup::where('username', $request->username)->First();

            if ($telegram_group) {
                return redirect()->route('admin.telegram_group.create')->with('success', 'группа c таким названием уже есть в списке');
            } else {
                $telegram_group = TelegramGroup::create([
                    'id' => rand(88888, 999999999),
                    'username' => $request->username,
                    'title' => 'не проверено'
                ]);
            }
            return redirect()->route('admin.telegram_group.index')->with('success', 'Телеграмм-группа добавлена');
        } else {
            return redirect()->route('admin.telegram_group.create')->with('success', 'Группы с таким названием не существует');
        }
    }

    public function destroy(TelegramGroup $telegram_group)
    {
        $telegram_group->telegram_messages()->delete();
        $telegram_group->delete();

        return redirect()->route('admin.telegram_group.index')->with('success', 'Телеграмм-группа удалена');
    }

    public function checkGroup($username)
    {
        $response = Http::withoutRedirecting()
            ->get("https://t.me/$username");

        if ($response->status() === 200) {
            return true; // Группа существует
        }

        return false; // Группа не найдена (status 404)
    }
}
