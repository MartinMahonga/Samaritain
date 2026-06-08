@php
use DistortedFusion\BladeForms\BladeForms;
@endphp
<x-dynamic-component
    :component="BladeForms::componentAliasWithPrefix('form-grid-column')"
    :attributes="$getColumnAttributeBag()->class([
        'flex flex-col gap-y-2'
    ])">
    <x-dynamic-component
        :component="BladeForms::componentAliasWithPrefix('form-label')"
        :label="$label ?? $getName()"
        :for="$getId()"
        :mark-required="$markRequired" />

    <div class="flex items-center rounded-md">
        <select id="{{ $getId() }}"

            @if($forNative())
            name="{{ $getName() }}"
            @endif

            @if($multiple)
            multiple
            @endif

            {{ $attributes->class([
                'form-select block w-full flex-grow relative',

                'text-base sm:text-sm leading-5 h-9',

                'py-2',
                'pl-4',
                'pr-8',

                'placeholder:text-[var(--muted-foreground)]',
                'text-inherit' => ! Str::contains($attributes->get('class'), 'text-'),
                'bg-[var(--input)] border-[var(--border)]',
                'ring-0 focus:border-[var(--primary)] focus:ring-2 focus:ring-[color-mix(in_oklab,var(--primary)_25%,transparent)]',
                'disabled:opacity-50',
                'rounded-l-md' => ($prefix ?? false) === false && ! Str::contains($attributes->get('class'), 'rounded-'),
                    'rounded-r-md' => ($suffix ?? false) === false && ! Str::contains($attributes->get('class'), 'rounded-'),
            ]) }}>
            @forelse($options as $key => $option)
                <option value="{{ $key }}" @if($isSelected($key)) selected="selected" @endif>
                    {{ $option }}
                </option>
            @empty
                {!! $slot !!}
            @endforelse
        </select>
    </div>

    @include('blade-forms::components.partials.description')

    @if($hasErrorAndShow($getErrorName()))
        <x-dynamic-component
            :component="BladeForms::componentAliasWithPrefix('form-errors')"
            :name="$getErrorName()" />
    @endif
</x-dynamic-component>
