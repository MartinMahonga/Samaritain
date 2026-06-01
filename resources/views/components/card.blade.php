@props([
    'placeholder' => true
])

<div {{ $attributes->merge([
    'class' => 'bg-zinc-900/20 backdrop-blur-md border border-zinc-800/80 rounded-xl relative overflow-hidden transition-all duration-300 hover:border-zinc-700/50 hover:bg-zinc-900/30 group shadow-lg shadow-black/20'
]) }}>
    
    @if ($slot->isEmpty() && $placeholder)
        <!-- Beautiful Shadcn-style placeholder grid/wireframe design -->
        <div class="absolute inset-0 flex flex-col justify-between p-4 pointer-events-none select-none">
            <!-- Decorative dots in the corners to look premium -->
            <div class="flex justify-between w-full">
                <div class="w-1.5 h-1.5 rounded-full bg-zinc-800 group-hover:bg-zinc-700 transition-colors"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-zinc-800 group-hover:bg-zinc-700 transition-colors"></div>
            </div>
            
            <!-- Central design element: minimalist crosshair or dash indicator -->
            <div class="self-center flex items-center justify-center relative w-full h-full min-h-[80px]">
                <!-- Subtle grid background lines -->
                <div class="absolute inset-0 border border-dashed border-zinc-800/40 rounded-lg m-6 group-hover:border-zinc-800/80 transition-colors"></div>
                
                <div class="flex flex-col items-center gap-1.5 z-10">
                    <div class="w-8 h-8 rounded-full bg-zinc-950 border border-zinc-800 flex items-center justify-center text-zinc-600 group-hover:text-zinc-400 group-hover:border-zinc-700 transition-all duration-300">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <div class="flex justify-between w-full">
                <div class="w-1.5 h-1.5 rounded-full bg-zinc-800 group-hover:bg-zinc-700 transition-colors"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-zinc-800 group-hover:bg-zinc-700 transition-colors"></div>
            </div>
        </div>
    @else
        <!-- Renders content inside slot -->
        <div class="p-5 w-full h-full flex flex-col">
            {{ $slot }}
        </div>
    @endif
</div>
