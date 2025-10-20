<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use danog\MadelineProto\API;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    private $madeline;

    public function __construct()
    {
        $this->madeline = new API(storage_path('telegram/session.madeline'));
        $this->madeline->start();
    }

    public function checkGroup(Request $request)
    {
        $request->validate([
            'username' => 'required|string|regex:/^@?[a-zA-Z0-9_]{5,32}$/'
        ]);

        try {
            $group = $this->madeline->getPwrChat($request->username);
            
            return response()->json([
                'exists' => true,
                'group' => [
                    'id' => $group['id'],
                    'title' => $group['title'],
                    'username' => $group['username'] ?? null,
                    'members_count' => $group['participants_count'] ?? null
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'exists' => false,
                'error' => 'Группа не найдена или недоступна'
            ], 404);
        }
    }
}