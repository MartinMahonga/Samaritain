<?php

use App\Models\City;
use App\Models\Parcelle;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();

    $city = City::create(['name' => 'Brazzaville']);

    $this->property = Property::factory()->create([
        'city_id' => $city->id,
        'is_active' => true,
        'is_verify' => true,
    ]);

    $this->parcel = Parcelle::create([
        'created_by' => $this->user->id,
        'titre' => 'Terrain de test',
        'description' => 'Description de test.',
        'localisation' => 'Brazzaville',
        'quartier' => 'Moungali',
        'ville' => 'Brazzaville',
        'superficie' => 500,
        'prix' => 12000000,
        'statut' => 'disponible',
        'reference' => 'REF-'.uniqid(),
        'viabilisee' => true,
    ]);
});

test('les visiteurs non connectes sont rediriges', function () {
    $this->get(route('favorite'))->assertRedirect(route('login'));

    $this->post(route('property.favorite.toggle', $this->property))
        ->assertRedirect(route('login'));

    $this->post(route('parcel.favorite', $this->parcel))
        ->assertRedirect(route('login'));
});

test('un utilisateur connecte peut ajouter/retirer une propriete de ses favoris via ajax', function () {
    $this->actingAs($this->user);

    // Ajouter
    $this->postJson(route('property.favorite.toggle', $this->property))
        ->assertOk()
        ->assertJson(['favorited' => true]);

    expect($this->user->fresh()->favorites->contains($this->property->id))->toBeTrue();

    // Retirer
    $this->postJson(route('property.favorite.toggle', $this->property))
        ->assertOk()
        ->assertJson(['favorited' => false]);

    expect($this->user->fresh()->favorites->contains($this->property->id))->toBeFalse();
});

test('un utilisateur connecte peut ajouter/retirer une parcelle de ses favoris via ajax', function () {
    $this->actingAs($this->user);

    // Ajouter
    $this->postJson(route('parcel.favorite', $this->parcel))
        ->assertOk()
        ->assertJson(['favorited' => true]);

    expect($this->user->fresh()->favoritesParcels->contains($this->parcel->id))->toBeTrue();

    // Retirer
    $this->postJson(route('parcel.favorite', $this->parcel))
        ->assertOk()
        ->assertJson(['favorited' => false]);

    expect($this->user->fresh()->favoritesParcels->contains($this->parcel->id))->toBeFalse();
});

test('un utilisateur connecte peut retirer une propriete de ses favoris avec la methode delete', function () {
    $this->actingAs($this->user);

    $this->user->favorites()->attach($this->property->id);
    expect($this->user->favorites->contains($this->property->id))->toBeTrue();

    $this->delete(route('property.favorite.destroy', $this->property))
        ->assertRedirect();

    expect($this->user->fresh()->favorites->contains($this->property->id))->toBeFalse();
});

test('un utilisateur connecte peut retirer une parcelle de ses favoris avec la methode delete', function () {
    $this->actingAs($this->user);

    $this->user->favoritesParcels()->attach($this->parcel->id);
    expect($this->user->favoritesParcels->contains($this->parcel->id))->toBeTrue();

    $this->delete(route('parcel.favorite.destroy', $this->parcel))
        ->assertRedirect();

    expect($this->user->fresh()->favoritesParcels->contains($this->parcel->id))->toBeFalse();
});

test('la page des favoris affiche les deux sections', function () {
    $this->actingAs($this->user);

    $this->user->favorites()->attach($this->property->id);
    $this->user->favoritesParcels()->attach($this->parcel->id);

    $this->get(route('favorite'))
        ->assertOk()
        ->assertSee('Biens immobiliers')
        ->assertSee('Parcelles favorites')
        ->assertSee($this->property->title)
        ->assertSee($this->parcel->titre);
});
