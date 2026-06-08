@props([
    'label' => '',
    'name',
    'type' => 'text',
])

<div>
    @if($label)
        <label
            for="{{ $name }}"
            class="block text-xs font-medium text-gray-700 mb-1"
        >
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $attributes->get('value')) }}"
        {{ $attributes->merge([
            'class' => 'w-full h-9 rounded-lg text-sm border border-gray-300 px-4 py-2 focus:outline-hidden focus:ring-2 focus:border-primary focus:ring-primary/10'
        ]) }}
    >

    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>