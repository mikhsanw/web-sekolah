<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('registers a user with detail payload and roles', function () {
    $adminRole = Role::create(['name' => 'Admin']);

    $response = $this->postJson('/api/register', [
        'name' => 'API User',
        'email' => 'api-user@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'hp' => '08123456789',
        'roles' => [$adminRole->id],
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.user.email', 'api-user@example.com')
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user',
                'token',
            ],
        ]);

    $user = User::where('email', 'api-user@example.com')->firstOrFail();

    expect($user->detail)->toBe(['hp' => '08123456789']);
    expect($user->hasRole('Admin'))->toBeTrue();
});

it('logs in an existing user and returns a token', function () {
    $user = User::factory()->create([
        'email' => 'login@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.user.email', 'login@example.com');

    expect($response->json('data.token'))->not->toBeEmpty();
});
