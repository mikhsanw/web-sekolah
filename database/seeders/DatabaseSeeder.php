<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Permission "sistem" — mengubah konfigurasi inti aplikasi: siapa boleh
     * apa (role & permission), struktur navigasi (menu), serta pengaturan
     * aplikasi (backup/Google Drive dan identitas website).
     *
     * Sengaja TIDAK disinkronkan ke role Admin. Mengubah hal-hal ini bisa
     * mengunci akses seluruh admin lain atau mengubah perilaku aplikasi
     * secara global, jadi dibatasi hanya untuk Super Admin (yang mem-bypass
     * seluruh pengecekan permission lewat Gate::before di AppServiceProvider
     * dan CheckRoutePermission — lihat kedua berkas itu).
     */
    private $permissionsSistem = [
        'roles list',
        'roles create',
        'roles edit',
        'roles delete',
        'menu list',
        'menu create',
        'menu edit',
        'menu delete',
        'settings list',
        'settings create',
        'settings edit',
        'settings delete',
        'pengaturan list',
        'pengaturan create',
        'pengaturan edit',
        'pengaturan delete',
    ];

    /**
     * Permission "operasional" — pekerjaan sehari-hari mengelola akun
     * pengguna dan konten website. Disinkronkan ke role Admin.
     *
     * Modul baru dari `php artisan make:crud` otomatis masuk ke sini
     * (marker gencrud-permissions), sehingga secara default berstatus
     * operasional/Admin kecuali sengaja dipindahkan ke $permissionsSistem.
     */
    private $permissionsOperasional = [
        'users list',
        'users create',
        'users edit',
        'users delete',
        'berita list',
        'berita create',
        'berita edit',
        'berita delete',
        'logs list',
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
        // gencrud-permissions
    ];

    /**
     * Permission role "Editor" — pekerjaan editorial konten website saja.
     * Tidak boleh menyentuh akun pengguna, data akademik (kelulusan/SPMB),
     * data referensi (pegawai/fasilitas/ekstrakurikuler), maupun konfigurasi
     * sistem (role, menu, pengaturan).
     */
    private $permissionsEditor = [
        'konten list',
        'berita list',
        'berita create',
        'berita edit',
        'berita delete',
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
        'profil-sekolah list',
    ];

    /**
     * Permission role "Operator Akademik" — mengelola periode kelulusan dan
     * penerimaan siswa baru (SPMB) saja. Tidak bisa mengubah konten website,
     * akun pengguna, atau konfigurasi sistem.
     */
    private $permissionsOperatorAkademik = [
        'kelulusan-grup list',
        'periode-kelulusan list',
        'periode-kelulusan create',
        'periode-kelulusan edit',
        'periode-kelulusan delete',
        'kelulusan list',
        'kelulusan create',
        'kelulusan edit',
        'kelulusan delete',
        'spmb-grup list',
        'periode-spmb list',
        'periode-spmb create',
        'periode-spmb edit',
        'periode-spmb delete',
        'spmb list',
        'spmb create',
        'spmb edit',
        'spmb delete',
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Semua permission dibuat lebih dulu (baik sistem maupun operasional):
        // rute, middleware, dan @can di Blade merujuk namanya langsung, jadi
        // harus selalu ada sebagai baris di tabel permissions terlepas dari
        // role mana yang diberi izinnya.
        foreach ([...$this->permissionsSistem, ...$this->permissionsOperasional, ...$this->permissionsEditor, ...$this->permissionsOperatorAkademik] as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Admin: hanya permission operasional. Tidak bisa mengubah role,
        // menu navigasi, maupun pengaturan aplikasi/website — itu tugas
        // Super Admin.
        $role = Role::firstOrCreate(['name' => 'Admin']);
        $role->syncPermissions($this->permissionsOperasional);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@example.com',
        ]);
        $user->assignRole([$role->id]);

        // Super Admin: tidak perlu permission eksplisit apa pun. Gate::before
        // (AppServiceProvider) dan CheckRoutePermission sudah meloloskan role
        // ini dari seluruh pengecekan, termasuk permission yang baru dibuat
        // lewat make:crud di kemudian hari.
        $superadminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $user = User::factory()->create([
            'name' => 'Example superadmin user',
            'email' => 'superadmin@example.com',
        ]);
        $user->assignRole($superadminRole);

        // Editor: hanya konten website. Tidak bisa mengelola akun pengguna,
        // data akademik, data referensi, maupun konfigurasi sistem.
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $editorRole->syncPermissions($this->permissionsEditor);

        $editor = User::factory()->create([
            'name' => 'Example editor user',
            'email' => 'editor@example.com',
        ]);
        $editor->assignRole($editorRole);

        // Operator Akademik: hanya kelulusan & SPMB. Tidak bisa konten,
        // akun pengguna, maupun konfigurasi sistem.
        $operatorRole = Role::firstOrCreate(['name' => 'Operator Akademik']);
        $operatorRole->syncPermissions($this->permissionsOperatorAkademik);

        $operator = User::factory()->create([
            'name' => 'Example operator user',
            'email' => 'operator@example.com',
        ]);
        $operator->assignRole($operatorRole);

        $this->call(MenuSeeder::class);
        $this->call(SekolahSeeder::class);
    }
}
