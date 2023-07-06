<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// redirect ke login
Route::get('/', function () {
    return redirect('/login');
});

// authentikasi
Auth::routes([
    'register' => false,
    'reset' => true
]);

// bungkus dengan middleware agar yang bisa akses route didalamnya hanya user yang sudah login.
Route::middleware('auth')->group(function () {
    // dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // bungkus route di bawah dengan middleware pengecekan admin agar user biasa tidak bisa akses route didalamnya.
    Route::middleware('can:is_admin')->group(function () {
        // perizinan
        Route::resource('/perizinan', App\Http\Controllers\Admin\PerizinanController::class)->only(['index', 'show', 'update']);

        // jadwal
        Route::resource('/jadwal', App\Http\Controllers\Admin\JadwalController::class)->only(['index', 'edit', 'update']);

        // users
        Route::resource('/users', App\Http\Controllers\Admin\UserController::class)->except(['show']);

        // export
        Route::get('/export', [App\Http\Controllers\ExportController::class, 'index'])->name('export');
        Route::post('/export', [App\Http\Controllers\ExportController::class, 'absensi'])->name('export.absensi');

        // riwayat
        Route::get('/users/{user}/riwayat', [App\Http\Controllers\Admin\RiwayatController::class, 'riwayat'])->name('users.riwayat');
        Route::get('/users/{user}/riwayat/{absensi}/detail', [App\Http\Controllers\Admin\RiwayatController::class, 'detail'])->name('users.riwayat.detail');
    });

    // absensi
    Route::get('/absensi', [App\Http\Controllers\AbsensiController::class, 'absensi'])->name('absensi');
    Route::post('/absensi', [App\Http\Controllers\AbsensiController::class, 'store'])->name('absensi.store');

    // riwayat
    Route::get('/riwayat', [App\Http\Controllers\RiwayatController::class, 'riwayat'])->name('riwayat');
    Route::get('/riwayat/{absensi}/detail', [App\Http\Controllers\RiwayatController::class, 'detail'])->name('riwayat.detail');

    // perizinan
    Route::resource('/izin', App\Http\Controllers\PerizinanController::class)->except(['create', 'edit', 'update', 'destroy'])->parameter('izin', 'perizinan');

    // profil
    Route::view('/profil', 'profil')->name('profil');
    Route::put('/profil/edit', App\Http\Controllers\UbahProfilController::class)->name('ubah-profil');
});
