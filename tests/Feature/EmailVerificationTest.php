<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

it('redirects unverified user to verification notice', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('property.create'))
        ->assertRedirect(route('verification.notice'));
});

it('allows verified user to access protected routes', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('property.create'))
        ->assertOk();
});

it('displays the verify email page', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('verification.notice'))
        ->assertOk()
        ->assertViewIs('auth.verify-email');
});

it('sends verification notification on request', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->post(route('verification.send'))
        ->assertRedirect();

    Notification::assertSentTo($user, VerifyEmail::class);
});

it('redirects already verified user away from verification notice', function () {
    $user = User::factory()->create(); // email_verified_at is set by default

    $this->actingAs($user)
        ->get(route('verification.notice'))
        ->assertRedirect(config('fortify.home'));
});

it('verifies email with a valid signed URL', function () {
    Event::fake();

    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $this->actingAs($user)
        ->get($verificationUrl)
        ->assertRedirect(config('fortify.home').'?verified=1');

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

it('does not verify email with an invalid hash', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => 'wrong-hash']
    );

    $this->actingAs($user)
        ->get($verificationUrl)
        ->assertForbidden();

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('throttles resend verification after 6 requests', function () {
    $user = User::factory()->unverified()->create();

    for ($i = 0; $i < 6; $i++) {
        $this->actingAs($user)->post(route('verification.send'));
    }

    $this->actingAs($user)
        ->post(route('verification.send'))
        ->assertStatus(429);
});
