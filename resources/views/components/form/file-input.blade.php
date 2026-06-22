@props([
    'label' => null,
    'name',
    'multiple' => false,
    'accept' => 'image/*',
    'required' => false,
    'value' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    @if ($value)
        <div class="mb-3 flex flex-wrap gap-2">
            @if (is_array($value))
                @foreach ($value as $image)
                    <img src="{{ asset($image) }}" alt="" class="w-16 h-16 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                @endforeach
            @else
                <img src="{{ asset($value) }}" alt="" class="w-16 h-16 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
            @endif
        </div>
    @endif

    <div class="relative">
        <input type="file"
            id="{{ $name }}"
            name="{{ $multiple ? $name . '[]' : $name }}"
            accept="{{ $accept }}"
            {{ $multiple ? 'multiple' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 dark:file:bg-primary/20 file:text-primary dark:file:text-primary-400 hover:file:bg-primary/20 dark:hover:file:bg-primary/30 cursor-pointer border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 dark:focus:ring-primary/30 focus:border-primary dark:focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed']) }}
        >
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
    @error($name . '.*')
        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>