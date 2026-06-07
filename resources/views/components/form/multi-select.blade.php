@props([
    'label' => '',
    'name',
    'options' => [],
    'value' => [],

    'optionValue' => 'id',
    'optionLabel' => 'name',
])

@php
    $formattedOptions = collect($options)
        ->map(function ($option, $key) use ($optionValue, $optionLabel) {
            if (is_array($option)) {
                return [
                    'value' => (string) $option[$optionValue],
                    'label' => $option[$optionLabel],
                ];
            }

            if (is_object($option)) {
                return [
                    'value' => (string) $option->{$optionValue},
                    'label' => $option->{$optionLabel},
                ];
            }

            return [
                'value' => (string) $key,
                'label' => $option,
            ];
        })
        ->values();

    $selectedValues = collect(old($name, $value))
        ->map(fn ($id) => (string) $id)
        ->values();
@endphp

<div
    x-data="{
        open: false,
        selected: @js($selectedValues),
        options: @js($formattedOptions),

        toggle(value) {
            value = String(value)

            if (this.selected.includes(value)) {
                this.selected = this.selected.filter(v => v !== value)
            } else {
                this.selected.push(value)
            }
        },

        getLabel(value) {
            const option = this.options.find(
                option => String(option.value) === String(value)
            )

            return option ? option.label : value
        }
    }"
    class="relative"
>
    @if($label)
        <label class="block text-xs font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <!-- Trigger -->
    <button
        type="button"
        @click="open = !open"
        class="w-full min-h-10 rounded-xl border border-gray-300 px-3 py-2 text-left flex flex-wrap gap-2 items-center focus:outline-none focus:ring-2 focus:border-primary focus:ring-primary/10"
    >
        <template x-if="selected.length === 0">
            <span class="text-gray-400 text-sm">
                Sélectionner...
            </span>
        </template>

        <template x-for="item in selected" :key="item">
            <span
                class="px-2 py-1 text-xs rounded-lg bg-primary/10 text-primary"
                x-text="getLabel(item)"
            ></span>
        </template>

        <div class="ml-auto">
            <i data-lucide="chevrons-up-down" class="w-4 h-4"></i>
        </div>
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        x-transition
        @click.outside="open = false"
        class="absolute z-50 mt-2 w-full bg-sidebar border border-gray-200 rounded-xl shadow-sm overflow-hidden"
    >
        <template x-for="option in options" :key="option.value">
            <button
                type="button"
                @click="toggle(option.value)"
                class="w-full px-4 py-3 text-left text-sm hover:bg-accent flex items-center justify-between"
            >
                <span x-text="option.label"></span>

                <svg
                    x-show="selected.includes(option.value)"
                    class="w-4 h-4 text-primary"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
            </button>
        </template>
    </div>

    <!-- Inputs cachés -->
    <template x-for="value in selected" :key="value">
        <input
            type="hidden"
            name="{{ $name }}[]"
            :value="value"
        >
    </template>

    @error($name)
        <p class="mt-1 text-xs text-red-600">
            {{ $message }}
        </p>
    @enderror

    @error($name . '.*')
        <p class="mt-1 text-xs text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>