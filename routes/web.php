<?php

use App\Http\Controllers\AgamaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProfileController;
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

Route::redirect('/', '/login', 301);

Auth::routes();

// admin
Route::middleware(['auth', 'is_active'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // pegawai
    Route::resource('pegawai', PegawaiController::class)->except('show');

    // metode-pembayaran
    Route::resource('metode-pembayaran', MetodePembayaranController::class)->except('show');

    // jabatan
    Route::resource('jabatan', JabatanController::class)->except('show');

    // agama
    Route::resource('agama', AgamaController::class)->except('show');
    // periode
    Route::resource('periode', PeriodeController::class)->except('show');

    // pengaturan
    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
});
