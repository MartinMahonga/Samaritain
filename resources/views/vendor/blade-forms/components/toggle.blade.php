@php
use DistortedFusion\BladeForms\BladeForms;
@endphp
<x-dynamic-component
    :component="BladeForms::componentAliasWithPrefix('form-grid-column')"
    :attributes="$getColumnAttributeBag()">
    <label class="form-toggle inline-flex items-start gap-x-2 w-full {{ $after ? 'flex-row-reverse justify-between' : 'flex-row' }}">
        <input class="sr-only peer"
            id="{{ $getId() }}"
            type="checkbox"
            value="{{ $value }}"

            @if($forNative())
            name="{{ $getName() }}"
            @endif

            @if($isChecked())
            checked="checked"
            @endif

            {{ $attributes }} />
        <div class="{{ implode(' ', [
            'flex-shrink-0 h-5 w-9 inline-block relative border-none absolute inset-0 rounded-full cursor-pointer',
            '[transition:color_.15s_ease-out]',
            'peer-focus:outline-none',

            'bg-[var(--border)] peer-checked:bg-[var(--primary)] dark:peer-checked:bg-[var(--primary)]',

            'after:content-[\'\'] after:h-4 after:w-4 after:shadow after:rounded-full after:absolute after:top-1/2 after:-translate-y-1/2 after:left-0.5 after:transition-all',
            'after:bg-[var(--background)] dark:after:bg-[var(--foreground)] dark:peer-checked:after:bg-[var(--primary-foreground)]',

            'peer-checked:after:translate-x-full',
            'peer-disabled:opacity-50 peer-disabled:cursor-not-allowed',
        ]) }}"></div>

        <div class="cursor-pointer peer-disabled:cursor-not-allowed flex flex-col gap-y-0">
            <span class="text-sm font-medium">{{ $label }}</span>

            @include('blade-forms::components.partials.description')
        </div>
    </label>

    @if($hasErrorAndShow($getErrorName()))
        <x-dynamic-component
            :component="BladeForms::componentAliasWithPrefix('form-errors')"
            :name="$getErrorName()" />
    @endif
</x-dynamic-component>
