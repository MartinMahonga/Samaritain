@props([
    'label' => '',
    'name',
    'type' => 'text',
    'icon' => null,
    'placeholder' => '',
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

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="{{ $icon }}" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
            </div>
        @endif

        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $attributes->get('value')) }}"
            {{ $attributes->merge([
                'class' => 'w-full h-9 rounded-lg text-sm border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-gray-800 dark:text-white focus:outline-hidden focus:ring-2 focus:border-primary dark:focus:border-primary focus:ring-primary/10 dark:focus:ring-primary/20 ' . ($icon ? 'pl-10' : 'pl-4')
            ]) }}
        >
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>