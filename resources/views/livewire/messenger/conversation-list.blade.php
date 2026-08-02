<div class="flex flex-col h-full overflow-hidden">
    <header class="px-4 py-3 border-b dark:border-gray-800">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold">Messages</h2>
        </div>
        <div class="relative">
            <flux:input
                placeholder="Rechercher..."
                wire:model.live="search"
            />
        </div>
    </header>

    <main class="flex-1 overflow-y-auto">
        @if($conversations->isEmpty())
            <div class="flex flex-col items-center justify-center h-full text-center p-6">
                <i data-lucide="message-circle-more" class="w-12 h-12 text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">Aucune conversation</p>
                <p class="text-gray-400 text-xs mt-1">Les conversations apparaîtront après la signature des contrats.</p>
            </div>
        @else
            <ul class="divide-y dark:divide-gray-800">
                @foreach($conversations as $conversation)
                    <li>
                        <button
                            wire:click="selectConversation({{ $conversation->id }})"
                            class="w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors flex items-start gap-3"
                        >
                            <x-ui.avatar class="w-10 h-10 shrink-0" />

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-medium text-sm truncate">
                                        {{ $conversation->other_user->name }}
                                    </h3>
                                    @if($conversation->last_message_at)
                                        <span class="text-xs text-gray-400 shrink-0">
                                            {{ $conversation->last_message_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 truncate mt-0.5">
                                    {{ $conversation->property->title ?? 'N/A' }}
                                </p>
                                @if($conversation->last_message)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate mt-1">
                                        {{ $conversation->last_message }}
                                    </p>
                                @endif
                            </div>

                            @if($conversation->unread_count > 0)
                                <span class="bg-blue-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shrink-0 mt-1">
                                    {{ $conversation->unread_count }}
                                </span>
                            @endif
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </main>
</div>