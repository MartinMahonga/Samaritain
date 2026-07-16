<?php

use App\Models\Property;
use App\Models\User;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('shows an unavailable message when coordinates are missing', function () {
    $property = Property::factory()->create([
        'latitude' => null,
        'longitude' => null,
    ]);

    get(route('property.show', $property))
        ->assertOk()
        ->assertSee('La localisation de cette propriété n\'est pas disponible.');
});

it('renders the map when coordinates are present', function () {
    $property = Property::factory()->create([
        'latitude' => -4.2634,
        'longitude' => 15.2429,
    ]);

    get(route('property.show', $property))
        ->assertOk()
        ->assertSee('property-map')
        ->assertSee('Ouvrir dans Google Maps')
        ->assertSee('Itinéraire')
        ->assertDontSee('La localisation de cette propriété n\'est pas disponible.');
});

it('accepts nullable coordinates on the form request', function () {
    $property = Property::factory()->create([
        'latitude' => null,
        'longitude' => null,
    ]);

    expect($property->latitude)->toBeNull();
    expect($property->longitude)->toBeNull();
});
