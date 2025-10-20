<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\Message\ChatRequest;
use App\Http\Requests\Profile\Message\StoreChatRequest;
use App\Models\Chat;
use App\Models\ChatParticipant;
use Illuminate\Support\Facades\Auth;

class MyMessengerController extends Controller
{
    public function index(ChatRequest $request)
    {
        $chat = null;

        if (isset($request->chat)) {
            $chat = Chat::with('messages')->where('uuid', $request->chat)->whereHas('participants', fn($q) => $q->where('participant_id', auth()->id()))
                ->with('participants')
                ->first();

            if (!$chat) {
                return redirect('messenger');
            }
        }

        return view('profile.pages.messenger.index', [
            'chat' => $chat
        ]);
    }

    public function store(StoreChatRequest $request)
    {
        $otherParticipantId = $request->user_id ?? $request->entity_id;
        $otherParticipantType = $request->user_id ? 'App\Models\User' : 'App\Models\Entity';

        $chat = $this->findExistingChat($otherParticipantId, $otherParticipantType);

        if (!$chat) {
            $chat = Chat::create([
                'type' => $request->user_id ? 'user_to_user' : 'user_to_entity'
            ]);

            // Добавляем участников
            ChatParticipant::create([
                'chat_id' => $chat->id,
                'participant_type' => 'App\Models\User',
                'participant_id' => Auth::id()
            ]);

            ChatParticipant::create([
                'chat_id' => $chat->id,
                'participant_type' => $otherParticipantType,
                'participant_id' => $otherParticipantId
            ]);
        }

        return view('profile.pages.messenger.index', [
            'chat' => $chat
        ]);
    }

    protected function findExistingChat($otherParticipantId, $otherParticipantType)
    {
        return Auth::user()->chats()
            ->whereHas('participants', function ($query) use ($otherParticipantId, $otherParticipantType) {
                $query->where('participant_id', $otherParticipantId)
                    ->where('participant_type', $otherParticipantType);
            })
            ->first();
    }
}
