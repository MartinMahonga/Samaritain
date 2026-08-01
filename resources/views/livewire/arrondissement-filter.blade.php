<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-foreground/80 mb-2">{{ $villeLabel }}</label>
        <select wire:model.live="ville" name="{{ $villeName }}" id="{{ $villeName }}" {{ $required ? 'required' : '' }} class="w-full px-4 py-2.5 border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent bg-background text-foreground">
            <option value="">{{ $villePlaceholder }}</option>
            @foreach ($villes as $city)
                <option value="{{ $city->name }}">{{ $city->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-foreground/80 mb-2">{{ $arrondissementLabel }}</label>
        <select wire:model.live="arrondissementId" name="{{ $arrondissementName }}" id="{{ $arrondissementName }}" {{ $required ? 'required' : '' }} class="w-full px-4 py-2.5 border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent bg-background text-foreground">
            <option value="">{{ $arrondissementPlaceholder }}</option>
            @foreach ($arrondissements as $arrondissement)
                <option value="{{ $arrondissement->id }}">{{ $arrondissement->name }}</option>
            @endforeach
        </select>
    </div>
</div>