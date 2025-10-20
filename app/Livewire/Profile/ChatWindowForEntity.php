<?php

namespace App\Livewire\Profile;

use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Chat;
use Livewire\Component;

class ChatWindowForEntity extends Component
{
    use WithPagination;

    public $chat;
    public $entityId;
    public $message = '';
    public $perPage = 100;
    public $hasMore = true;
    public $isLoading = false;
    public $initialLoad = true; // 1

    protected $listeners = ['loadMore'];

    public function mount($chatId, $entityId)
    {
        $this->chat = Chat::find($chatId);
        $this->entityId = $entityId;
        $this->markMessagesAsRead();
    }

    public function getMessagesProperty()
    {
        $query = $this->chat->messages()
            ->with(['user', 'entity'])
            ->orderBy('created_at', 'asc')
            ->limit($this->perPage);
  
        return $query->get();
    }

    public function sendMessage()
    {
        $this->validate(['message' => 'required|string|max:1000']);

        $this->chat->messages()->create([
            'user_id' => auth()->id(),
            'entity_id' => $this->entityId,
            'content' => $this->message
        ]);

        $this->chat->update([
            'last_message_at' => now(),
            'last_message' => $this->message
        ]);

        $this->reset('message');
        $this->dispatch('scroll-to-bottom');
    }

    #[On('loadMore')]
    public function loadMore()
    {
        if ($this->isLoading || !$this->hasMore) return;

        $this->isLoading = true;
        $this->perPage += 10;
        $this->initialLoad = false;
        $this->hasMore = $this->chat->messages()->count() > $this->perPage;
        $this->isLoading = false;

        $this->dispatch('messages-loaded');
    }

    public function markMessagesAsRead()
    {
        $this->chat->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function render()
    {
        $messages = $this->messages;
        $groupedMessages = $messages->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });

        return view('livewire.profile.chat-window-for-entity', [
            'groupedMessages' => $groupedMessages,
            'otherUser' => $this->chat->otherParticipant()->user
        ]);
    }
}
