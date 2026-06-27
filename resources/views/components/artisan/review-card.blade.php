@props(['review'])

<div class="bg-card rounded-xl border border-border p-4">
    <div class="flex items-start justify-between mb-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-muted overflow-hidden flex-shrink-0">
                @if($review->user->profile_image)
                    <img src="{{ $review->user->profileUrl() }}" alt="{{ $review->user->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary font-bold">
                        {{ substr($review->user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div>
                <p class="font-medium text-foreground">{{ $review->user->name }}</p>
                <p class="text-xs text-muted-foreground">{{ $review->created_at->diffForHumans() }}</p>
            </div>
        </div>
        <div class="flex text-warning">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-muted-foreground' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                </svg>
            @endfor
        </div>
    </div>
    
    @if($review->comment)
        <p class="text-muted-foreground text-sm leading-relaxed">{{ $review->comment }}</p>
    @endif
</div>