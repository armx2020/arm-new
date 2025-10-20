<div>
    @php
        $participant = $chat->otherParticipant();
    @endphp

    <div class="h-[26rem] flex flex-col bg-white rounded-lg shadow" wire:poll.5000ms="markMessagesAsRead"
        x-data="{
            init() {
                // Первоначальный скролл вниз
                this.$nextTick(() => {
                    this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
                });
        
                // Автоскролл при новых сообщениях
                Livewire.on('scroll-to-bottom', () => {
                    this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
                });
        
                // Сохраняем позицию при подгрузке старых сообщений
                Livewire.on('messages-loaded', () => {
                    const container = this.$refs.messagesContainer;
                    const oldScrollHeight = container.scrollHeight;
        
                    this.$nextTick(() => {
                        container.scrollTop = container.scrollHeight - oldScrollHeight;
                    });
                });
        
                // Подгрузка при скролле вверх
                this.$refs.messagesContainer.addEventListener('scroll', (e) => {
                    if (e.target.scrollTop < 100 && @this.hasMore && !@this.isLoading) {
                        Livewire.dispatch('loadMore');
                    }
                });
            }
        }">
        <!-- Заголовок чата -->
        <div class="border-b">
            @if ($participant instanceof App\Models\User)
                <div class="flex items-center p-3 rounded-lg">
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
                    <div class="ml-3 flex-1">
                        <div class="flex justify-between items-center">
                            <h4 class="text-sm font-medium text-gray-900">
                                {{ $participant->firstname }}</h4>
                            <div class="ml-auto flex space-x-2">
                                <button class="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @else
                <div class="flex items-center p-3 rounded-lg">
                    <div class="relative">
                        @if ($participant->primaryImageView)
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="{{ asset('storage/' . $participant->primaryImageView->path) }}" alt="User avatar">
                        @else
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ url('/image/no-image.png') }}"
                                alt="image" />
                        @endif
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex justify-between items-center">
                            <h4 class="text-sm font-medium text-gray-900">
                                {{ $participant->name }}</h4>
                            <div class="ml-auto flex space-x-2">
                                <button class="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>

        <!-- Сообщения -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 flex flex-col-reverse" id="messages-container"
            x-ref="messagesContainer" style="scrollbar-width: none; -ms-overflow-style: none;">
            <style>
                #messages-container::-webkit-scrollbar {
                    display: none;
                }
            </style>

            <div class="space-y-4">
                @foreach ($groupedMessages as $date => $messages)
                    <!-- Дата -->
                    <div class="flex justify-center">
                        <span class="bg-gray-100 text-xs text-gray-500 px-2 py-1 rounded-full">
                            {{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM YYYY') }}
                        </span>
                    </div>

                    <!-- Сообщения за дату -->
                    @foreach ($messages as $message)
                        <div class="flex {{ $message->user_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="flex flex-col max-w-xs">
                                <div
                                    class="{{ $message->user_id == auth()->id() ? 'bg-blue-100' : 'bg-gray-100' }} rounded-lg p-2">
                                    <p>{{ $message->content }}</p>
                                </div>
                                <p
                                    class="text-xs text-gray-500 {{ $message->user_id == auth()->id() ? 'text-end' : 'text-start' }}">
                                    {{ $message->created_at->format('H:i') }}
                                    @if ($message->user_id == auth()->id())
                                        @if ($message->is_read)
                                            <span class="text-blue-500">✓✓</span>
                                        @else
                                            <span>✓</span>
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <!-- Индикатор загрузки -->
            @if ($isLoading)
                <div class="flex justify-center py-2">
                    <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            @endif
        </div>

        <!-- Форма отправки -->
        <div class="p-4 border-t">
            <form wire:submit.prevent="sendMessage">
                <div class="flex space-x-2">
                    <input type="text" wire:model="message"
                        class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Напишите сообщение..." wire:keydown.enter.prevent="sendMessage">
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition disabled:opacity-50"
                        wire:loading.attr="disabled" @if (empty(trim($message))) disabled @endif>
                        <span wire:loading wire:target="sendMessage">
                            <svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="sendMessage">Отправить</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
