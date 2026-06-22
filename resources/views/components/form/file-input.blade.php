@props([
    'label' => '',
    'name',
    'value' => [],
    'multiple' => false,
    'accept' => 'images/*',
    'helper' => ''
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-xs font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    @if (!empty($value))
        <div class="mb-3 flex flex-wrap gap-2">
            @foreach ($value as $image)
                <img src="{{ asset($image) }}" alt="" class="w-20 h-20 rounded-lg object-cover border">
            @endforeach
        </div>
    @endif

    <input type="file" id="{{ $name }}" name="{{ $name }}[]" accept="{{ $accept }}" helper="{{ $helper }}"
        @if ($multiple) multiple @endif
        {{ $attributes->merge([
            'class' => '
                                w-full
                                text-sm
                                rounded-lg
                                border
                                border-gray-300
                                px-4
                                py-2
                                file:mr-4
                                file:rounded-md
                                file:border-0
                                file:bg-primary
                                file:px-4
                                file:py-2
                                file:text-white
                                hover:file:opacity-90
                            ',
        ]) }}>

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
