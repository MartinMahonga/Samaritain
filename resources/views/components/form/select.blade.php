@props([
    'label' => '',
    'name',
    'options' => [],
    'placeholder' => null,
    'value' => null,
    'icon' => null,
    'required' => false,

    // Pour les tableaux associatifs
    'optionValue' => 'id',
    'optionLabel' => 'name',
])

<div>
    @if ($label)
        <label
            for="{{ $name }}"
            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
        >
            {{ $label }}
            @if($required)
                <span class="text-red-500 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i data-lucide="{{ $icon }}" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
            </div>
        @endif

        <select
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => '
                    w-full
                    h-10
                    rounded-xl
                    border
                    border-gray-200
                    dark:border-gray-700
                    bg-white
                    dark:bg-gray-800
                    text-sm
                    text-gray-700
                    dark:text-gray-300
                    shadow-xs
                    appearance-none
                    transition-all
                    duration-200
                    focus:outline-none
                    focus:border-primary
                    dark:focus:border-primary
                    focus:ring-4
                    focus:ring-primary/10
                    dark:focus:ring-primary/20
                    ' . ($icon ? 'pl-10' : 'pl-4') . '
                    pr-10
                ',
            ]) }}
        >
            @if ($placeholder)
                <option value="" class="text-gray-400 dark:text-gray-500">
                    {{ $placeholder }}
                </option>
            @endif

            @foreach ($options as $key => $option)
                @php
                    $isArray = is_array($option);
                    $isObject = is_object($option);

                    $optionKey = $isArray
                        ? $option[$optionValue]
                        : ($isObject ? $option->{$optionValue} : $key);

                    $optionText = $isArray
                        ? $option[$optionLabel]
                        : ($isObject ? $option->{$optionLabel} : $option);
                @endphp

                <option
                    value="{{ $optionKey }}"
                    @selected(old($name, $value) == $optionKey)
                    class="text-gray-700 dark:text-gray-300 dark:bg-gray-800"
                >
                    {{ $optionText }}
                </option>
            @endforeach
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
            <i data-lucide="chevrons-up-down" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
        </div>
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-600 dark:text-red-400">
            {{ $message }}
        </p>
    @enderror
</div>