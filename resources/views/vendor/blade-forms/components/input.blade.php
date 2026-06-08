@php
use DistortedFusion\BladeForms\BladeForms;
use Illuminate\Support\Str;
@endphp
<x-dynamic-component
    :component="BladeForms::componentAliasWithPrefix('form-grid-column')"
    :attributes="$getColumnAttributeBag()->merge([
        'hidden' => $type === 'hidden',
    ])->class([
        'flex flex-col gap-y-2'
    ])">
    <x-dynamic-component
        :component="BladeForms::componentAliasWithPrefix('form-label')"
        :label="$label ?? $getName()"
        :for="$getId()"
        :mark-required="$markRequired" />

    <div class="flex items-stretch {{ Str::contains($attributes->get('class'), 'rounded-')
            ? 'rounded-'.Str::before(Str::after($attributes->get('class'), 'rounded-'), ' ')
            : 'rounded-md'
        }}"
        @if($attributes->has('wire:ignore'))
        wire:ignore
        @endif
        >
        @if($prefix ?? false)
            <span {{ $prefix->attributes->class([
                'form-input inline-flex items-center w-auto rounded-l-md border-r-0',
                'text-base leading-none sm:text-sm sm:leading-none',
                'bg-[var(--input)] text-[var(--muted-foreground)] border-[var(--border)]',
            ]) }}>
                {{ $prefix }}
            </span>
        @endif
        <div class="w-full flex-grow relative">
            @if($iconPrefix ?? false)
                <span {{ $iconPrefix->attributes->class([
                    'absolute left-2 inset-y-0',
                    'flex items-center',
                ]) }}>
                    {{ $iconPrefix }}
                </span>
            @endif
            <input id="{{ $getId() }}"
                type="{{ $type }}"

                @if($forNative())
                name="{{ $getName() }}"
                value="{{ $value }}"
                @endif

                {{ $attributes->except(['wire:ignore'])->class([
                    'form-input block w-full',

                    'text-base sm:text-sm leading-5 h-9',

                    'py-2',
                    'pl-4' => ($iconPrefix ?? false) !== false,
                    'pr-4' => ($iconSuffix ?? false) !== false,

                    'placeholder:text-[var(--muted-foreground)]',
                    'text-inherit' => ! Str::contains($attributes->get('class'), 'text-'),
                    'bg-[var(--input)] border-[var(--border)]',
                    'ring-0 focus:border-[var(--primary)] focus:ring-2 focus:ring-[color-mix(in_oklab,var(--primary)_25%,transparent)]',
                    'disabled:opacity-50 read-only:opacity-50',

                    'rounded-l-md' => ($prefix ?? false) === false && ! Str::contains($attributes->get('class'), 'rounded-'),
                    'rounded-r-md' => ($suffix ?? false) === false && ! Str::contains($attributes->get('class'), 'rounded-'),
                ]) }} />
            @if($iconSuffix ?? false)
                <span {{ $iconSuffix->attributes->class([
                    'absolute right-2 inset-y-0',
                    'flex items-center',
                ]) }}>
                    {{ $iconSuffix }}
                </span>
            @endif
        </div>
        @if($suffix ?? false)
            <span {{ $suffix->attributes->class([
                'form-input inline-flex items-center w-auto rounded-r-md border-l-0',
                'text-base leading-none sm:text-sm sm:leading-none',
                'bg-[var(--input)] text-[var(--muted-foreground)] border-[var(--border)]',
            ]) }}>
                {{ $suffix }}
            </span>
        @endif
    </div>

    @include('blade-forms::components.partials.description')

    @if($hasErrorAndShow($getErrorName()))
        <x-dynamic-component
            :component="BladeForms::componentAliasWithPrefix('form-errors')"
            :name="$getErrorName()" />
    @endif
</x-dynamic-component>
