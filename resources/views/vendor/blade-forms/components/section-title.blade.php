<x-heading :heading-level="$headingLevel" size="flex" {{ $attributes->class([
    'font-sans-heading font-medium text-base',
]) }}>{{ $slot }}</x-heading>
