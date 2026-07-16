<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div class="flex flex-col transition-all h-full overflow-hidden">
    <header class="px-3 z-10 sticky top-0 bg-white dark:bg-gray-900 border-b dark:border-gray-800 py-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="message-circle-more" class="w-5 h-5 text-gray-400"></i>
                    <h5 class="font-extrabold text-lg">Messages</h5>
                </div>
            </div>
        </div>
    </header>

    <main class="overflow-y-scroll overflow-hidden grow h-full relative" style="contain:content">
        <ul class="p-2 grid w-full spacey-y-2">
            <li class="py-3 hover:bg-gray-50 rounded-2xl dark:hover:bg-gray-700/70 transition-colors duration-150 flex gap-4 relative w-full cursor-pointer px-2">
                <a href="#" class="shrink-0">
                    <x-ui.avatar />
                </a>

                <aside class="grid grid-cols-12 w-full">
                    <a href="#" class="col-span-11 border-gray-200 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1">
                        {{-- Nom et date --}}
                        <div class="flex justify-between w-full items-center">
                            <h6 class="truncate font-medium tracking-wider text-sm">
                                John Doe
                            </h6>
                            <small>3 min</small>
                        </div>

                        {{-- Message body --}}
                        <div class="flex gap-x-2 items-center">
                            <i data-lucide="check-check" class="w-5 h-5 text-blue-500"></i>
                            <p class="grow truncate text-xs">
                                Contenue du message que John Doe a envoyé
                            </p>
                        </div>
                    </a>

                    <div>
                        <flux:dropdown>
                            <flux:button size="sm" variant="ghost">
                                <flux:icon.ellipsis-vertical />
                            </flux:button>

                            <flux:menu>
                                <flux:menu.item icon="user">Voir le profil</flux:menu.item>

                                <flux:menu.item variant="danger" icon="trash">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </aside>
            </li>
        </ul>
    </main>
</div>