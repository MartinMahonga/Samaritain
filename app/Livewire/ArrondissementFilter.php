<?php

namespace App\Livewire;

use App\Models\Arrondissement;
use App\Models\City;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ArrondissementFilter extends Component
{
    public string $ville = '';

    public ?int $villeId = null;

    public ?int $arrondissementId = null;

    public string $villeName = 'ville';

    public string $arrondissementName = 'arrondissement_id';

    public string $villeLabel = 'Ville';

    public string $arrondissementLabel = 'Arrondissement';

    public string $villePlaceholder = 'Toutes les villes';

    public string $arrondissementPlaceholder = 'Toutes les arrondissements';

    public bool $required = false;

    public string $villeValue = 'name';

    public function mount(
        ?string $ville = null,
        ?int $villeId = null,
        ?int $arrondissementId = null,
        string $villeName = 'ville',
        string $arrondissementName = 'arrondissement_id',
        string $villeLabel = 'Ville',
        string $arrondissementLabel = 'Arrondissement',
        string $villePlaceholder = 'Toutes les villes',
        string $arrondissementPlaceholder = 'Toutes les arrondissements',
        bool $required = false,
        string $villeValue = 'name',
    ): void {
        $this->ville = $ville ?? '';
        $this->villeId = $villeId;
        $this->arrondissementId = $arrondissementId;
        $this->villeName = $villeName;
        $this->arrondissementName = $arrondissementName;
        $this->villeLabel = $villeLabel;
        $this->arrondissementLabel = $arrondissementLabel;
        $this->villePlaceholder = $villePlaceholder;
        $this->arrondissementPlaceholder = $arrondissementPlaceholder;
        $this->required = $required;
        $this->villeValue = $villeValue;
    }

    public function getVillesProperty()
    {
        return City::select(['id', 'name'])->orderBy('name')->get();
    }

    public function getArrondissementsProperty()
    {
        if ($this->villeId) {
            return Arrondissement::where('city_id', $this->villeId)
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get();
        }

        if ($this->ville !== '') {
            return Arrondissement::whereHas('city', fn ($q) => $q->where('name', $this->ville))
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    public function updatedVilleId(): void
    {
        $this->arrondissementId = null;
    }

    public function updatedVille(): void
    {
        $this->arrondissementId = null;

        if ($this->ville !== '') {
            $city = City::where('name', $this->ville)->first();
            $this->villeId = $city?->id;
        } else {
            $this->villeId = null;
        }
    }

    public function render(): View
    {
        return view('livewire.arrondissement-filter', [
            'villes' => $this->villes,
            'arrondissements' => $this->arrondissements,
        ]);
    }
}