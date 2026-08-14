<?php

use App\Exceptions\PawaPayException;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PawapayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);
});

describe('index', function () {
    it('redirects guests to login', function () {
        get(route('owner.payouts.index'))->assertRedirect(route('login'));
    });

    it('shows the payouts list to an authenticated owner', function () {
        actingAs($this->owner);

        Transaction::factory()->create([
            'user_id' => $this->owner->id,
            'type' => 'payout',
            'status' => 'completed',
            'amount' => 50000,
            'payout_id' => (string) Str::uuid(),
            'currency' => 'XAF',
        ]);

        get(route('owner.payouts.index'))
            ->assertOk()
            ->assertSee('Virements Mobile Money')
            ->assertSee('50');
    });

    it('shows an empty state when no payouts exist', function () {
        actingAs($this->owner);

        get(route('owner.payouts.index'))
            ->assertOk()
            ->assertSee('Aucun virement effectué');
    });
});

describe('create', function () {
    it('redirects guests to login', function () {
        get(route('owner.payouts.create'))->assertRedirect(route('login'));
    });

    it('renders the create form for authenticated owners', function () {
        actingAs($this->owner);

        get(route('owner.payouts.create'))
            ->assertOk()
            ->assertSee('Nouveau virement Mobile Money')
            ->assertSee('Numéro Mobile Money');
    });
});

describe('store', function () {
    it('redirects guests to login', function () {
        post(route('owner.payouts.store'))->assertRedirect(route('login'));
    });

    it('validates required fields', function () {
        actingAs($this->owner);

        post(route('owner.payouts.store'), [])
            ->assertSessionHasErrors(['phone_number', 'amount']);
    });

    it('returns an error when the provider cannot be predicted', function () {
        actingAs($this->owner);

        $this->mock(PawapayService::class, function ($mock) {
            $mock->shouldReceive('predictProvider')
                ->once()
                ->andThrow(new PawaPayException('Numéro invalide', 422, '{}'));
        });

        post(route('owner.payouts.store'), [
            'phone_number' => '242999000000',
            'amount' => 10000,
        ])->assertSessionHasErrors(['phone_number']);
    });

    it('creates a payout transaction and redirects on success', function () {
        actingAs($this->owner);

        $this->mock(PawapayService::class, function ($mock) {
            $mock->shouldReceive('predictProvider')
                ->once()
                ->andReturn([
                    'msisdn' => '242065000000',
                    'correspondents' => [['correspondent' => 'AIRTEL_COG']],
                ]);

            $mock->shouldReceive('createPayout')
                ->once()
                ->andReturn(['status' => 'ACCEPTED']);
        });

        post(route('owner.payouts.store'), [
            'phone_number' => '242065000000',
            'amount' => 20000,
            'description' => 'Test virement',
        ])
            ->assertRedirect(route('owner.payouts.index'))
            ->assertSessionHas('success');

        expect(Transaction::where('type', 'payout')->where('user_id', $this->owner->id)->exists())->toBeTrue();
    });
});
