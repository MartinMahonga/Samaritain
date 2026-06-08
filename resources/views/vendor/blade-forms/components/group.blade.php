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
        :label="$label"
        :mark-required="$markRequired"
        class="cursor-default" />

    <div class="flex {{ $inline ?? false ? 'flex-wrap gap-x-6' : 'flex flex-col gap-y-2' }}{{ $label ?? false && $input ?? false ? ' ' : null }}">
        {!! $slot !!}
    </div>

    @if($showErrors && $hasErrorAndShow($getErrorName()))
        <x-dynamic-component
            :component="BladeForms::componentAliasWithPrefix('form-errors')"
            :name="$getErrorName()" />
    @endif
</x-dynamic-component>
