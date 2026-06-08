@props(['icon'])
<x-dynamic-component :component="$icon" {{ $attributes->class([
    'text-[var(--muted-foreground)]',
    'w-4' => ! Str::contains($attributes->get('class'), ['w-']),
    'h-4' => ! Str::contains($attributes->get('class'), ['h-']),
]) }} />
