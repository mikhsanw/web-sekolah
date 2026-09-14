<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('menolak registrasi publik lewat API karena route sudah dimatikan', function () {
    // Akun hanya boleh dibuat Super Admin lewat panel admin (lihat
    // routes/api.php) — pastikan endpoint publiknya tetap tidak ada.
    $this->postJson('/api/register', [
        'name' => 'Percobaan Registrasi',
        'email' => 'percobaan-registrasi@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertNotFound();

    expect(User::where('email', 'percobaan-registrasi@example.com')->exists())->toBeFalse();
});

it('menolak registrasi publik lewat web karena route sudah dimatikan', function () {
    // Lihat routes/web.php — Auth::routes(['register' => false]).
    $this->get('/register')->assertNotFound();
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
