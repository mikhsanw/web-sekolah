<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MenuSeeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    private $permissions = [
        'roles list',
        'roles create',
        'roles edit',
        'roles delete',
        'users list',
        'users create',
        'users edit',
        'users delete',
        'products list',
        'products create',
        'products edit',
        'products delete',
        'settings list',
        'settings create',
        'settings edit',
        'settings delete',
        'berita list',
        'berita create',
        'berita edit',
        'berita delete',
        'logs list',
        'menu list',
        'menu create',
        'menu edit',
        'menu delete',
        // modul website sekolah
        'konten list',
        'profil-sekolah list',
        'kelulusan-grup list',
        'spmb-grup list',
        'halaman list',
        'halaman create',
        'halaman edit',
        'halaman delete',
        'agenda list',
        'agenda create',
        'agenda edit',
        'agenda delete',
        'galeri list',
        'galeri create',
        'galeri edit',
        'galeri delete',
        'prestasi list',
        'prestasi create',
        'prestasi edit',
        'prestasi delete',
        'pegawai list',
        'pegawai create',
        'pegawai edit',
        'pegawai delete',
        'ekstrakurikuler list',
        'ekstrakurikuler create',
        'ekstrakurikuler edit',
        'ekstrakurikuler delete',
        'fasilitas list',
        'fasilitas create',
        'fasilitas edit',
        'fasilitas delete',
        'periode-kelulusan list',
        'periode-kelulusan create',
        'periode-kelulusan edit',
        'periode-kelulusan delete',
        'kelulusan list',
        'kelulusan create',
        'kelulusan edit',
        'kelulusan delete',
        'periode-spmb list',
        'periode-spmb create',
        'periode-spmb edit',
        'periode-spmb delete',
        'spmb list',
        'spmb create',
        'spmb edit',
        'spmb delete',
        'pengaturan list',
        'pengaturan create',
        'pengaturan edit',
        'pengaturan delete',
        // gencrud-permissions
    ];

    public function run(): void
    {
        // User::factory(10)->create();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $role = Role::firstOrCreate(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@example.com',
        ]);
        $user->assignRole([$role->id]);

        $superadminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $user = User::factory()->create([
            'name' => 'Example superadmin user',
            'email' => 'superadmin@example.com',
        ]);
        $user->assignRole($superadminRole);

        $this->call(MenuSeeder::class);
        $this->call(SekolahSeeder::class);
    }
}
