@props([
    'label' => '',
    'name',
    'rows' => 4,
])

<div>
    @if($label)
        <label
            for="{{ $name }}"
            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
        >
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge([
            'class' => 'w-full text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-gray-800 dark:text-white focus:outline-hidden focus:ring-2 focus:border-primary dark:focus:border-primary focus:ring-primary/10 dark:focus:ring-primary/20'
        ]) }}
    >{{ old($name, $attributes->get('value')) }}</textarea>

    @error($name)
        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>