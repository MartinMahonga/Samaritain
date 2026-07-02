@props([
    'options' => [],      // ['macon' => ['label' => 'Maçon', 'icon' => 'hammer'], ...]
    'name' => 'category',
    'value' => null,
    'allLabel' => 'Tous les métiers',
])

@php
    $current = $value ?? request($name);
@endphp

<div class="flex gap-2 overflow-x-scroll [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
    {{-- Pastille "Tous" pour réinitialiser ce filtre --}}
    <a href="{{ request()->fullUrlWithoutQuery([$name, 'page']) }}"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-full border text-sm font-medium transition-all duration-200 whitespace-nowrap
            {{ !$current
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-white dark:bg-gray-800 border-accent dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-primary hover:text-primary dark:hover:text-primary-400' }}">
        <i data-lucide="list" class="w-4 h-4"></i>
        {{ $allLabel }}
    </a>

    @foreach ($options as $option)
        @php
            $label = is_array($option) ? $option['label'] : $option;
            $icon = is_array($option) ? ($option['icon'] ?? 'briefcase') : 'briefcase';
            $isActive = (string) $current === (string) $label->id;
        @endphp
        <a href="{{ $isActive
                ? request()->fullUrlWithoutQuery([$name, 'page'])
                : request()->fullUrlWithQuery([$name => $label->id, 'page' => null]) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-full border text-sm font-medium transition-all duration-200 whitespace-nowrap
                {{ $isActive
                    ? 'bg-primary border-primary text-white shadow-sm'
                    : 'bg-white dark:bg-gray-800 border-accent dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-primary hover:text-primary dark:hover:text-primary-400' }}">
            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
            {{ $label->name }}
        </a>
    @endforeach
</div>