<div class="flex flex-col h-full">
    @if(! $conversation || ! $otherUser)
        <div class="flex flex-col items-center justify-center h-full text-center p-6">
            <i data-lucide="message-circle" class="w-12 h-12 text-gray-300 mb-3"></i>
            <p class="text-gray-500">Choisissez une conversation pour commencer</p>
        </div>
    @else
        <header class="px-4 py-3 border-b dark:border-gray-800 flex items-center gap-3">
            <x-ui.avatar :user="$otherUser" class="w-8 h-8" />
            <div>
                <h3 class="font-medium text-sm">{{ $otherUser->name }}</h3>
                <p class="text-xs text-gray-500">{{ $conversation->contract->property->title ?? '' }}</p>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-800/30">
            @foreach($messages as $message)
                @php
                    $isMine = $message->sender_id === auth()->id();
                @endphp
                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] px-4 py-2 rounded-2xl text-sm {{ $isMine ? 'bg-blue-600 text-white rounded-br-sm' : 'bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-bl-sm' }}">
                        <p class="break-words">{{ $message->body }}</p>
                        <span class="text-xs {{ $isMine ? 'text-blue-100' : 'text-gray-400' }} block mt-1">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </main>

        <footer class="p-3 border-t dark:border-gray-800 bg-white dark:bg-gray-900">
            <div class="flex gap-2">
                <flux:textarea
                    placeholder="Écrire un message..."
                    rows="1"
                    class="!py-2"
                    wire:model="body"
                    wire:keydown.enter="sendMessage"
                />
                <flux:button
                    variant="primary"
                    icon="send"
                    onclick="$wire.call('sendMessage')"
                />
            </div>
        </footer>
    @endif
</div>