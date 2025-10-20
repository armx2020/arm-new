<?php

namespace App\Livewire\Profile;

use App\Models\Chat;
use Livewire\Component;
use Livewire\WithPagination;

class ChatList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 4;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getChatsProperty()
    {
        return auth()->user()->chats()
            ->withCount([
                'messages as unread_count' => function ($query) {
                    $query->where('user_id', '!=', auth()->id())
                        ->where('is_read', false);
                }
            ])
            ->with(['participants.participant'])
            ->when($this->search, function ($query) {
                $query->whereHas('participants', function ($q) {
                    $q->where('participant_id', '!=', auth()->id()) // Исключаем текущего пользователя
                        ->whereHasMorph(
                            'participant',
                            ['App\Models\User', 'App\Models\Entity'],
                            function ($query, $type) {
                                if ($type === 'App\Models\User') {
                                    $query->where('firstname', 'like', '%' . $this->search . '%');
                                } else {
                                    $query->where('name', 'like', '%' . $this->search . '%');
                                }
                            }
                        );
                });
            })
            ->orderByDesc(
                fn($q) => $q->select('created_at')
                    ->from('messages')
                    ->whereColumn('chat_id', 'chats.id')
                    ->latest()
                    ->limit(1)
            )
            ->simplePaginate($this->perPage);
    }

    public function markAsRead($chatUuid)
    {
        $chat = Chat::where('uuid', $chatUuid)->first();

        if ($chat) {
            $chat->messages()
                ->where('user_id', '!=', auth()->id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $this->emit('unreadCountUpdated'); // Для обновления счетчика
        }
    }

    public function render()
    {
        return view('livewire.profile.chat-list', [
            'chats' => $this->chats
        ]);
    }
}
