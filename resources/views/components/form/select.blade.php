@props([
    'label' => '',
    'name',
    'options' => [],
    'placeholder' => null,
    'value' => null,

    // Pour les tableaux associatifs
    'optionValue' => 'id',
    'optionLabel' => 'name',
])

<div>
    @if ($label)
        <label
            for="{{ $name }}"
            class="block text-xs font-medium text-gray-700 mb-1"
        >
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $attributes->merge([
                'class' => '
                    w-full
                    h-10
                    rounded-xl
                    border
                    border-gray-200
                    pl-4
                    pr-10
                    text-sm
                    text-gray-700
                    shadow-xs
                    appearance-none
                    transition-all
                    duration-200
                    focus:outline-none
                    focus:border-primary
                    focus:ring-4
                    focus:ring-primary/10
                ',
            ]) }}
        >
            @if ($placeholder)
                <option value="">
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
                >
                    {{ $optionText }}
                </option>
            @endforeach
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
            <i data-lucide="chevrons-up-down" class="w-4 h-4"></i>
        </div>
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>