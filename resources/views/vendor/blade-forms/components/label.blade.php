@if($label)
    <label {{ $attributes->class([
        'form-label block text-sm leading-none font-medium',
        'text-inherit' => ! Str::contains($attributes->get('class'), 'text-'),
        'cursor-pointer' => ! Str::contains($attributes->get('class'), 'cursor-'),
    ]) }}>
        <span>{!! $label !!}</span>

        @if($markRequired)
            <span class="text-[var(--muted-foreground)] text-sm">&#42;</span>
        @endif
    </label>
@endif
