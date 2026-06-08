@error($name, $bag)
    <p {!! $attributes->class([
        'text-[var(--danger)] text-xs italic'
    ]) !!}>{{ $message }}</p>
@enderror
