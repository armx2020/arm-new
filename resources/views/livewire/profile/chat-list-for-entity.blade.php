<div class="space-y-2">

    <div class="relative pt-1">
        <input type="text" placeholder="Поиск чатов..." wire:model.live.debounce.300ms="search"
            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    @if ($chats->isEmpty())
        <p class="text-sm text-center pt-5">У вас нет чатов</p>
    @endif

    @foreach ($chats as $chat)
        @php
            $participant = $chat->otherUserParticipant();
            $unreadCount = $chat->unread_count;
        @endphp

        @if ($participant instanceof App\Models\User)
            <a href="{{ url()->current() }}?chat={{ $chat->uuid }}"
                wire:click.prevent="markAsRead('{{ $chat->uuid }}')"
                onclick="window.location.href = this.getAttribute('href')"
                class="block hover:bg-gray-100 rounded-lg transition relative">

                <div
                    class="flex items-center p-3 rounded-lg hover:bg-gray-100 @if ($unreadCount) bg-gray-200 @endif cursor-pointer transition">
                    <div class="relative">
                        @if ($participant->image)
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="{{ asset('storage/' . $participant->image) }}" alt="User avatar">
                        @else
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ url('/image/no-image.png') }}"
                                alt="User avatar" />
                        @endif
                        @if ($participant->isOnline())
                            <span
                                class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                        @endif
                    </div>
                    <div class="ml-3 flex-1 h-10 overflow-hidden">
                        <div class="flex justify-between items-center">
                            <h4 class="text-sm font-medium text-gray-900">
                                {{ $participant->firstname }}</h4>
                            <span
                                class="text-xs text-gray-500">{{ $chat->last_message_at?->format('H:i') ?? $chat->created_at?->format('H:i') }}</span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $chat->last_message ?? 'сообщений нет' }}</p>
                    </div>
                </div>

                @if ($unreadCount)
                    <span
                        class="absolute right-[-0.5rem] top-[-0.5rem] bg-blue-500 px-3 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </a>
        @endif
    @endforeach

    <div>
        {{ $chats->links() }}
    </div>
</div>
