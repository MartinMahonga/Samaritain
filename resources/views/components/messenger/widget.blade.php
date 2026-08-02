@props([])

<div class="h-[calc(100vh-120px)]">
    <div class="flex h-full bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg overflow-hidden shadow-sm">
        <div class="w-full md:w-80 border-r dark:border-gray-800 h-full">
            <livewire:messenger.conversation-list />
        </div>
        <div class="hidden md:block flex-1 h-full">
            <livewire:messenger.chat-window />
        </div>
    </div>
</div>