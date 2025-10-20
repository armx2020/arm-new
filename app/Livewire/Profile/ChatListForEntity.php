<?php

namespace App\Livewire\Profile;

use App\Models\Chat;
use App\Models\Entity;
use Livewire\Component;
use Livewire\WithPagination;

class ChatListForEntity extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $entityId = null;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function mount($entityId = null)
    {
        $this->entityId = $entityId;
    }

    public function getChatsProperty()
    {
        $entity = Entity::select('id', 'user_id')->where('user_id', auth()->id())->where('id', $this->entityId)->First();

        return $entity->chats()
            ->withCount([
                'messages as unread_count' => function ($query) {
                    $query->where('user_id', '!=', auth()->id())
                        ->where('is_read', false);
                }
            ])
            ->with(['participants.participant'])
            ->when($this->search, function ($query) use ($entity) {
                $query->whereHas('participants', function ($q) use ($entity) {
                    $q->where('participant_id', '!=', $entity->id) // Исключаем текущего пользователя
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
        return view('livewire.profile.chat-list-for-entity', [
            'chats' => $this->chats
        ]);
    }
}
