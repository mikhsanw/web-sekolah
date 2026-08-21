<?php
use Illuminate\Support\Facades\Route;

//file
Route::prefix('file')
    ->as('file')
    ->group(function () {
        Route::get('stream/{id}/{name}', 'FileController@getFile');
        Route::get('download/{id}/{name}', 'FileController@downloadFile');
        // Penghapusan harus lewat verb DELETE agar terlindungi CSRF (bukan GET).
        Route::delete('delete/{id}/{name}', 'FileController@deleteFile');
        Route::post('upload-image-editor', 'FileController@handleEditorImageUpload');
    });
//dashboard
Route::get('/home', 'DashboardController@index')->name('home');
Route::post('/sorted', 'MenuController@sorted')->name('menu.sorted');
//menus
Route::prefix('menu')
    ->as('menu')
    ->group(function () {
        Route::get('/data', 'MenuController@data');
        Route::get('delete/{id}', 'MenuController@delete');
    });
Route::resource('menu', 'MenuController');

//logs
Route::resource('logs', 'ActivityLogController');

//settings
Route::prefix('settings')
    ->as('settings.')
    ->group(function () {
        Route::get('/', 'SettingController@index')->name('index');
        Route::post('/backup', 'SettingController@backupFiles')->name('backup.files');
        Route::post('/update', 'SettingController@update')->name('update');
    });

//users
Route::prefix('users')
    ->as('users')
    ->group(function () {
        Route::get('/data', 'UserController@data');
        Route::get('delete/{id}', 'UserController@delete');
    });
Route::resource('users', 'UserController');
//roles
Route::resources(['roles' => RoleController::class]);
//berita
Route::prefix('berita')
    ->as('berita')
    ->group(function () {
        Route::get('delete/{id}', 'BeritaController@delete');
    });
Route::resource('berita', 'BeritaController');

/*
|--------------------------------------------------------------------------
| Modul Website Sekolah
|--------------------------------------------------------------------------
*/

//pengaturan website
Route::prefix('pengaturan')
    ->as('pengaturan.')
    ->group(function () {
        Route::get('/', 'PengaturanController@index')->name('index');
        Route::post('/', 'PengaturanController@update')->name('update');
        Route::delete('berkas/{kunci}', 'PengaturanController@hapusBerkas')
            ->middleware('permission:pengaturan delete')
            ->name('berkas.hapus');
    });

//halaman statis
Route::prefix('halaman')
    ->as('halaman')
    ->group(function () {
        Route::get('delete/{id}', 'HalamanController@delete');
    });
Route::resource('halaman', 'HalamanController');

//agenda
Route::prefix('agenda')
    ->as('agenda')
    ->group(function () {
        Route::get('delete/{id}', 'AgendaController@delete');
    });
Route::resource('agenda', 'AgendaController');

//ekstrakurikuler
Route::prefix('ekstrakurikuler')
    ->as('ekstrakurikuler')
    ->group(function () {
        Route::get('delete/{id}', 'EkstrakurikulerController@delete');
    });
Route::resource('ekstrakurikuler', 'EkstrakurikulerController');

//guru & tendik
Route::prefix('pegawai')
    ->as('pegawai')
    ->group(function () {
        Route::get('delete/{id}', 'PegawaiController@delete');
    });
Route::resource('pegawai', 'PegawaiController');

//prestasi
Route::prefix('prestasi')
    ->as('prestasi')
    ->group(function () {
        Route::get('delete/{id}', 'PrestasiController@delete');
    });
Route::resource('prestasi', 'PrestasiController');

//sarana prasarana
Route::prefix('fasilitas')
    ->as('fasilitas')
    ->group(function () {
        Route::get('delete/{id}', 'FasilitasController@delete');
    });
Route::resource('fasilitas', 'FasilitasController');

//galeri
Route::prefix('galeri')
    ->as('galeri')
    ->group(function () {
        Route::get('delete/{id}', 'GaleriController@delete');
    });
Route::resource('galeri', 'GaleriController');

//periode kelulusan
Route::prefix('periode-kelulusan')
    ->as('periode-kelulusan')
    ->group(function () {
        Route::get('delete/{id}', 'PeriodeKelulusanController@delete');
    });
Route::resource('periode-kelulusan', 'PeriodeKelulusanController');

//data kelulusan (termasuk import & export excel)
Route::prefix('kelulusan')
    ->as('kelulusan.')
    ->group(function () {
        Route::get('delete/{id}', 'KelulusanController@delete');
        Route::get('template', 'KelulusanController@template')
            ->middleware('permission:kelulusan create')->name('template');
        Route::get('import', 'KelulusanController@formImport')
            ->middleware('permission:kelulusan create')->name('import.form');
        Route::post('import', 'KelulusanController@import')
            ->middleware('permission:kelulusan create')->name('import');
        Route::get('export', 'KelulusanController@export')
            ->middleware('permission:kelulusan list')->name('export');
    });
Route::resource('kelulusan', 'KelulusanController');

//periode spmb
Route::prefix('periode-spmb')
    ->as('periode-spmb')
    ->group(function () {
        Route::get('delete/{id}', 'PeriodeSpmbController@delete');
    });
Route::resource('periode-spmb', 'PeriodeSpmbController');

//pendaftar spmb
Route::prefix('spmb')
    ->as('spmb.')
    ->group(function () {
        Route::get('delete/{id}', 'SpmbController@delete');
        Route::get('export', 'SpmbController@export')
            ->middleware('permission:spmb list')->name('export');
    });
Route::resource('spmb', 'SpmbController');

//gencrud
