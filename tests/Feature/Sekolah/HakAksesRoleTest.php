<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->admin = User::where('email', 'admin@example.com')->firstOrFail();
    $this->superAdmin = User::where('email', 'superadmin@example.com')->firstOrFail();

    // Modul Settings (backup) membaca disk 'google' secara eager; fake-kan
    // agar test tidak bergantung pada kredensial Google Drive sungguhan.
    Storage::fake('google');
});

it('menolak Admin membuka modul sistem (role, menu, pengaturan, settings)', function (string $url) {
    $this->actingAs($this->admin)->get($url)->assertForbidden();
})->with([
    'admin/roles',
    'admin/roles/create',
    'admin/menu',
    'admin/menu/create',
    'admin/pengaturan',
    'admin/settings',
]);

it('mengizinkan Admin membuka modul operasional (konten & akun pengguna)', function (string $url) {
    $this->actingAs($this->admin)->get($url)->assertOk();
})->with([
    'admin/berita',
    'admin/halaman',
    'admin/agenda',
    'admin/galeri',
    'admin/prestasi',
    'admin/pegawai',
    'admin/ekstrakurikuler',
    'admin/fasilitas',
    'admin/periode-kelulusan',
    'admin/kelulusan',
    'admin/periode-spmb',
    'admin/spmb',
    'admin/users',
]);

it('mengizinkan Super Admin membuka seluruh modul sistem sekaligus operasional', function (string $url) {
    $this->actingAs($this->superAdmin)->get($url)->assertOk();
})->with([
    'admin/roles',
    'admin/menu',
    'admin/pengaturan',
    'admin/settings',
    'admin/berita',
    'admin/users',
]);

it('menolak Admin memberi role Super Admin ke pengguna baru lewat request mentah', function () {
    $this->actingAs($this->admin)
        ->post(route('users.store'), [
            'name' => 'Percobaan Eskalasi',
            'email' => 'eskalasi@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles' => ['Super Admin'],
        ])
        ->assertSessionHasErrors('roles.0');

    expect(User::where('email', 'eskalasi@gmail.com')->exists())->toBeFalse();
});

it('menolak Admin menaikkan role pengguna lain menjadi Super Admin lewat request mentah', function () {
    $target = User::factory()->create(['email' => 'target@gmail.com']);
    $target->assignRole('Admin');

    $this->actingAs($this->admin)
        ->put(route('users.update', $target->id), [
            'name' => $target->name,
            'email' => $target->email,
            'roles' => ['Super Admin'],
        ])
        ->assertSessionHasErrors('roles.0');

    expect($target->fresh()->hasRole('Super Admin'))->toBeFalse();
});

it('mengizinkan Super Admin memberi role Super Admin', function () {
    $target = User::factory()->create(['email' => 'target-super@gmail.com']);
    $target->assignRole('Admin');

    $this->actingAs($this->superAdmin)
        ->put(route('users.update', $target->id), [
            'name' => $target->name,
            'email' => $target->email,
            'roles' => ['Super Admin'],
        ])
        ->assertSessionDoesntHaveErrors();

    expect($target->fresh()->hasRole('Super Admin'))->toBeTrue();
});

it('tidak menampilkan menu sistem pada sidebar Admin', function () {
    $response = $this->actingAs($this->admin)->get('admin/home');

    $response->assertOk()
        ->assertDontSee(route('roles.index'), false)
        ->assertDontSee(route('menu.index'), false)
        ->assertDontSee(route('pengaturan.index'), false);
});

it('menampilkan menu sistem pada sidebar Super Admin', function () {
    $response = $this->actingAs($this->superAdmin)->get('admin/home');

    $response->assertOk()
        ->assertSee(route('roles.index'), false)
        ->assertSee(route('menu.index'), false)
        ->assertSee(route('pengaturan.index'), false);
});

it('menyusun permission "sistem" dan "operasional" tanpa tumpang tindih', function () {
    $seeder = new DatabaseSeeder;
    $reflection = new ReflectionClass($seeder);

    $sistem = $reflection->getProperty('permissionsSistem');
    $sistem->setAccessible(true);
    $operasional = $reflection->getProperty('permissionsOperasional');
    $operasional->setAccessible(true);

    $irisan = array_intersect($sistem->getValue($seeder), $operasional->getValue($seeder));

    expect($irisan)->toBeEmpty();

    foreach (['roles list', 'menu list', 'settings list', 'pengaturan list'] as $permission) {
        expect($sistem->getValue($seeder))->toContain($permission);
        expect($operasional->getValue($seeder))->not->toContain($permission);
    }
});
