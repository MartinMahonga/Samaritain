<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

it('displays the forgot password page', function () {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertViewIs('pages.auth.forgot-password');
});

it('sends a password reset link for a valid email', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertSessionHas('status', Password::RESET_LINK_SENT);

    Notification::assertSentTo($user, ResetPassword::class);
});

it('returns an error for an unknown email', function () {
    $this->post(route('password.email'), ['email' => 'unknown@example.com'])
        ->assertSessionHasErrors('email');
});

it('displays the reset password page with a valid token', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->get(route('password.reset', ['token' => $token]))
        ->assertOk()
        ->assertViewIs('pages.auth.reset-password');
});

it('resets the password with a valid token', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'NewSecurePass#123',
        'password_confirmation' => 'NewSecurePass#123',
    ])->assertSessionHas('status', Password::PASSWORD_RESET);

    expect(Hash::check('NewSecurePass#123', $user->fresh()->password))->toBeTrue();
});

it('fails to reset password with an invalid token', function () {
    $user = User::factory()->create();

    $this->post(route('password.update'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'NewSecurePass#123',
        'password_confirmation' => 'NewSecurePass#123',
    ])->assertSessionHasErrors('email');
});

it('fails to reset password when passwords do not match', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'NewSecurePass#123',
        'password_confirmation' => 'DifferentPass#456',
    ])->assertSessionHasErrors('password');
});

it('validates that email is required for password reset link', function () {
    $this->post(route('password.email'), [])
        ->assertSessionHasErrors('email');
});
