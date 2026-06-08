@php
use DistortedFusion\BladeForms\BladeForms;
@endphp
<x-dynamic-component
    :component="BladeForms::componentAliasWithPrefix('form-grid-column')"
    :attributes="$getColumnAttributeBag()->class([
        'flex flex-col gap-y-2'
    ])">
    <label class="inline-flex items-center cursor-pointer [&:has(input:disabled)]:cursor-not-allowed">
        <input id="{{ $getId() }}"
            type="checkbox"
            value="{{ $value }}"

            @if($forNative())
            name="{{ $getName() }}"
            @endif

            @if($isChecked())
            checked="checked"
            @endif

            {{ $attributes->class([
                'size-4 appearance-none rounded relative focus:ring-0 focus:ring-offset-0',
                'text-[var(--primary)] bg-[var(--input)] border border-[var(--border)]',
                'focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--primary)]',

                'before:absolute before:inset-1 before:-translate-x-0.25 before:translate-y-0.125 before:hidden',
                'before:w-2 before:h-1 before:-rotate-45 before:border-l-2 before:border-b-2',
                'before:border-[var(--foreground)]',

                'hover:before:block focus-visible:before:block checked:before:block',

                'checked:border-[var(--primary)] checked:bg-none checked:bg-[var(--primary)]',
                'checked:before:border-[var(--primary-foreground)]',

                'disabled:opacity-50'
            ]) }} />
        <span class="ml-2 font-medium">{{ $label }}</span>
    </label>

    @include('blade-forms::components.partials.description')

    @if($hasErrorAndShow($getErrorName()))
        <x-dynamic-component
            :component="BladeForms::componentAliasWithPrefix('form-errors')"
            :name="$getErrorName()" />
    @endif
</x-dynamic-component>
