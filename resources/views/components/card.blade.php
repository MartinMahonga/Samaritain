@props([
    'placeholder' => true
])

<div {{ $attributes->merge([
    'class' => 'bg-[var(--card)]/90 backdrop-blur-md border border-[var(--border)] rounded-xl relative overflow-hidden transition-all duration-300 hover:border-[var(--sidebar-border)] hover:bg-[var(--card)] group shadow-lg shadow-black/20'
]) }}>
    
    @if ($slot->isEmpty() && $placeholder)
        <!-- Beautiful Shadcn-style placeholder grid/wireframe design -->
        <div class="absolute inset-0 flex flex-col justify-between p-4 pointer-events-none select-none">
            <!-- Decorative dots in the corners to look premium -->
            <div class="flex justify-between w-full">
                <div class="w-1.5 h-1.5 rounded-full bg-[var(--border)] group-hover:bg-[var(--sidebar-border)] transition-colors"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-[var(--border)] group-hover:bg-[var(--sidebar-border)] transition-colors"></div>
            </div>
            
            <!-- Central design element: minimalist crosshair or dash indicator -->
            <div class="self-center flex items-center justify-center relative w-full h-full min-h-[80px]">
                <!-- Subtle grid background lines -->
                <div class="absolute inset-0 border border-dashed border-[var(--sidebar-border)]/40 rounded-lg m-6 group-hover:border-[var(--sidebar-border)]/80 transition-colors"></div>
                
                <div class="flex flex-col items-center gap-1.5 z-10">
                    <div class="w-8 h-8 rounded-full bg-[var(--primary)] border border-[var(--sidebar-border)] flex items-center justify-center text-[var(--primary-foreground)] group-hover:text-[var(--foreground)] group-hover:border-[var(--sidebar-border)] transition-all duration-300">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <div class="flex justify-between w-full">
                <div class="w-1.5 h-1.5 rounded-full bg-[var(--border)] group-hover:bg-[var(--sidebar-border)] transition-colors"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-[var(--border)] group-hover:bg-[var(--sidebar-border)] transition-colors"></div>
            </div>
        </div>
    @else
        <!-- Renders content inside slot -->
        <div class="p-5 w-full h-full flex flex-col">
            {{ $slot }}
        </div>
    @endif
</div>
