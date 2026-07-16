<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div class="fixed h-full flex bg-white dark:bg-gray-900 border dark:border-gray-800 lg:shadow-sm overflow-hidden inset-0 lg:top-16 lg:inset-x-2 m-auto lg:h-[90%] rounded-lg">
    <div class="relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border dark:border-gray-800">
        <livewire:chat.chat-list />
    </div>

    <div class="hidden md:grid w-full border-l dark:border-gray-800 h-full relative overflow-y-auto" style="contain:content;">
        <div class="m-auto text-center justify-center flex flex-col gap-3">
            <div class="font-medium text-sm flex flex-col items-center justify-center gap-2">
                <i data-lucide="message-circle-more" class="w-6 h-6 text-gray-400"></i>
                <span>Choisie une conversation pour commencer</span>
            </div>
        </div>
    </div>
</div>