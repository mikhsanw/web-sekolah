<?php

use Illuminate\Support\Facades\File;

uses()->group('console');

beforeEach(function () {
    $this->backupFiles = [
        base_path('routes/backend.php'),
        base_path('routes/api.php'),
        database_path('seeders/DatabaseSeeder.php'),
        database_path('seeders/MenuSeeder.php'),
    ];

    $this->backups = [];

    foreach ($this->backupFiles as $file) {
        $this->backups[$file] = File::get($file);
    }
});

afterEach(function () {
    foreach ($this->backups as $file => $content) {
        File::put($file, $content);
    }

    $cleanupPaths = [
        app_path('Http/Controllers/Backend/PressReleasesController.php'),
        app_path('Http/Controllers/Api/AnnouncementsController.php'),
        app_path('Http/Requests/PressReleaseRequest.php'),
        app_path('Http/Requests/AnnouncementRequest.php'),
        app_path('Http/Resources/AnnouncementResource.php'),
        app_path('Models/PressRelease.php'),
        app_path('Models/Announcement.php'),
    ];

    foreach ($cleanupPaths as $path) {
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    $cleanupDirectories = [
        resource_path('views/backend/pressreleases'),
    ];

    foreach ($cleanupDirectories as $directory) {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
    }

    foreach (glob(database_path('migrations/*create_press_releases_table.php')) as $file) {
        File::delete($file);
    }

    foreach (glob(database_path('migrations/*create_announcements_table.php')) as $file) {
        File::delete($file);
    }
});

it('generates backend crud files plus route permission and menu registrations', function () {
    $this->artisan('make:crud', [
        'crud_name' => 'PressRelease',
        'columns' => 'title:string,is_active:boolean',
    ])
        ->expectsConfirmation('Do you want to create relationships between this model and an other one?', 'no')
        ->assertExitCode(0);

    expect(File::exists(app_path('Http/Controllers/Backend/PressReleasesController.php')))->toBeTrue();
    expect(File::exists(app_path('Http/Requests/PressReleaseRequest.php')))->toBeTrue();
    expect(File::exists(app_path('Models/PressRelease.php')))->toBeTrue();
    expect(File::isDirectory(resource_path('views/backend/pressreleases')))->toBeTrue();
    expect(glob(database_path('migrations/*create_press_releases_table.php')))->not->toBeEmpty();

    expect(File::get(base_path('routes/backend.php')))
        ->toContain("Route::resource('pressreleases', 'PressReleasesController');");

    expect(File::get(database_path('seeders/DatabaseSeeder.php')))
        ->toContain("'pressreleases list'")
        ->toContain("'pressreleases delete'");

    expect(File::get(database_path('seeders/MenuSeeder.php')))
        ->toContain("'code' => 'pressreleases'")
        ->toContain("'model' => 'App\\\\Models\\\\PressRelease'");
});

it('removes backend crud route permission and menu registrations', function () {
    $this->artisan('make:crud', [
        'crud_name' => 'PressRelease',
        'columns' => 'title:string,is_active:boolean',
    ])
        ->expectsConfirmation('Do you want to create relationships between this model and an other one?', 'no')
        ->assertExitCode(0);

    $this->artisan('rm:crud', [
        'crud_name' => 'PressRelease',
        '--force' => true,
    ])->assertExitCode(0);

    expect(File::get(base_path('routes/backend.php')))->not->toContain("Route::resource('pressreleases', 'PressReleasesController');");
    expect(File::get(database_path('seeders/DatabaseSeeder.php')))->not->toContain("'pressreleases list'");
    expect(File::get(database_path('seeders/MenuSeeder.php')))->not->toContain("'code' => 'pressreleases'");
});

it('generates api crud files plus route and permission registrations', function () {
    $this->artisan('make:rest-api', [
        'crud_name' => 'Announcement',
        'columns' => 'title:string,published_at:date',
    ])
        ->expectsConfirmation('Do you want to create relationships between this model and an other one?', 'no')
        ->assertExitCode(0);

    expect(File::exists(app_path('Http/Controllers/Api/AnnouncementsController.php')))->toBeTrue();
    expect(File::exists(app_path('Http/Requests/AnnouncementRequest.php')))->toBeTrue();
    expect(File::exists(app_path('Http/Resources/AnnouncementResource.php')))->toBeTrue();
    expect(File::exists(app_path('Models/Announcement.php')))->toBeTrue();
    expect(glob(database_path('migrations/*create_announcements_table.php')))->not->toBeEmpty();

    expect(File::get(base_path('routes/api.php')))
        ->toContain("Route::apiResource('announcements', \\\\App\\\\Http\\\\Controllers\\\\Api\\\\AnnouncementsController::class);");

    expect(File::get(database_path('seeders/DatabaseSeeder.php')))
        ->toContain("'announcements list'")
        ->toContain("'announcements delete'");
});

it('removes api crud route and permission registrations', function () {
    $this->artisan('make:rest-api', [
        'crud_name' => 'Announcement',
        'columns' => 'title:string,published_at:date',
    ])
        ->expectsConfirmation('Do you want to create relationships between this model and an other one?', 'no')
        ->assertExitCode(0);

    $this->artisan('rm:rest-api', [
        'crud_name' => 'Announcement',
        '--force' => true,
    ])->assertExitCode(0);

    expect(File::get(base_path('routes/api.php')))->not->toContain("Route::apiResource('announcements', \\\\App\\\\Http\\\\Controllers\\\\Api\\\\AnnouncementsController::class);");
    expect(File::get(database_path('seeders/DatabaseSeeder.php')))->not->toContain("'announcements list'");
});
