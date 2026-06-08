@php
use DistortedFusion\BladeForms\BladeForms;
@endphp
@if($description ?? false)
    @if(is_string($description))
        <x-dynamic-component :component="BladeForms::componentAliasWithPrefix('form-help')">
            {{ $description }}
        </x-dynamic-component>
    @elseif($description ?? false)
        {{ $description }}
    @endif
@endif
