<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminRole = Role::create(['name' => 'Admin']);
    $this->superAdminRole = Role::create(['name' => 'Super Admin']);
});

it('hides super admin users from non super admin listings', function () {
    $actor = User::factory()->create(['email' => 'admin-list@example.com']);
    $actor->assignRole($this->adminRole);

    $superAdmin = User::factory()->create(['email' => 'superadmin-list@example.com']);
    $superAdmin->assignRole($this->superAdminRole);

    $normalUser = User::factory()->create(['email' => 'member@example.com']);
    $normalUser->assignRole($this->adminRole);

    Sanctum::actingAs($actor);

    $response = $this->getJson('/api/users');

    $response->assertOk();
    $response->assertJsonMissing(['email' => 'superadmin-list@example.com']);
    $response->assertJsonFragment(['email' => 'member@example.com']);
});

it('prevents non super admin from assigning super admin role', function () {
    $actor = User::factory()->create(['email' => 'admin-store@example.com']);
    $actor->assignRole($this->adminRole);

    Sanctum::actingAs($actor);

    $response = $this->postJson('/api/users', [
        'name' => 'Restricted User',
        'email' => 'restricted@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'roles' => [$this->superAdminRole->id],
    ]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('users', ['email' => 'restricted@example.com']);
});

it('prevents non super admin from accessing super admin role detail', function () {
    $actor = User::factory()->create(['email' => 'admin-role@example.com']);
    $actor->assignRole($this->adminRole);

    Sanctum::actingAs($actor);

    $response = $this->getJson('/api/roles/' . $this->superAdminRole->id);

    $response->assertForbidden();
});

it('allows super admin to delete a regular role', function () {
    $actor = User::factory()->create(['email' => 'superadmin-delete@example.com']);
    $actor->assignRole($this->superAdminRole);

    $editorRole = Role::create(['name' => 'Editor']);

    Sanctum::actingAs($actor);

    $response = $this->deleteJson('/api/roles/' . $editorRole->id);

    $response
        ->assertOk()
        ->assertJsonPath('status', 'success');

    $this->assertDatabaseMissing('roles', ['id' => $editorRole->id]);
});
