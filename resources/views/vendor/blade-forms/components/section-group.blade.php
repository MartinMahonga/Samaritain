<div {{ $attributes->class([
    'flex flex-col gap-y-4 [&_section:not(:last-child)]:pb-4',
    'divide-y divide-[var(--border)]',
]) }}>
    {{ $slot }}
</div>
