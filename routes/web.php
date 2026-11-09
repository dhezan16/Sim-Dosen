<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\KegiatanDosenController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Support\KegiatanDosen;
use App\Support\MasterData;
use Illuminate\Support\Facades\Route;

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', DashboardController::class)->name('dashboard');

    // Akun milik pengguna yang sedang login (semua peran)
    Route::get('/akun', [AkunController::class, 'edit'])->name('akun.edit');
    Route::put('/akun', [AkunController::class, 'update'])->name('akun.update');

    // Dosen — hak akses per peran diatur oleh App\Policies\DosenPolicy
    Route::get('/dosen/export', [DosenController::class, 'export'])->name('dosen.export');
    Route::resource('dosen', DosenController::class);

    // Riwayat pendidikan & Tri Dharma milik dosen
    Route::controller(KegiatanDosenController::class)
        ->prefix('/dosen/{dosen}/{jenis}')
        ->name('kegiatan.')
        ->whereIn('jenis', array_keys(KegiatanDosen::definisi()))
        ->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->whereNumber('id')->name('edit');
            Route::put('/{id}', 'update')->whereNumber('id')->name('update');
            Route::delete('/{id}', 'destroy')->whereNumber('id')->name('destroy');
        });

    // Khusus admin
    Route::middleware('role:'.User::ADMIN)->group(function () {
        // Struktur institusi
        Route::resource('fakultas', FakultasController::class)
            ->except('show')
            ->parameters(['fakultas' => 'fakultas']);
        Route::resource('prodi', ProgramStudiController::class)->except('show');

        // Data referensi (jabatan, pangkat, status, dll.)
        Route::controller(MasterDataController::class)
            ->prefix('/master/{jenis}')
            ->name('master.')
            ->whereIn('jenis', array_keys(MasterData::JENIS))
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->whereNumber('id')->name('update');
                Route::delete('/{id}', 'destroy')->whereNumber('id')->name('destroy');
            });

        // Manajemen pengguna & peran
        Route::resource('users', UserController::class)->except('show');
    });
});
