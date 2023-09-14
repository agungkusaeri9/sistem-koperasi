<?php

use App\Http\Controllers\AgamaController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisSimpananController;
use App\Http\Controllers\LamaAngsuranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PinjamanAngsuranController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimpananShrController;
use App\Http\Controllers\SimpananWajibController;
use App\Models\JenisSimpanan;
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

    // anggota
    Route::resource('anggota', AnggotaController::class);

    // pengaturan
    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // lama-angsuran
    Route::resource('lama-angsuran', LamaAngsuranController::class);

    // jenis-simpanan
    Route::resource('jenis-simpanan', JenisSimpananController::class);

    // pinjaman
    Route::post('pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.filter');
    Route::resource('pinjaman', PinjamanController::class)->except('store');
    Route::post('pinjaman/create', [PinjamanController::class, 'store'])->name('pinjaman.store');
    Route::post('pinjaman/export-pdf/{kode}', [PinjamanController::class, 'export_pdf'])->name('pinjaman.export-pdf');

    // pinjaman angsuran
    Route::post('pinjaman-angsuran/{id}', [PinjamanAngsuranController::class, 'update'])->name('pinjaman-angsuran.update');
    Route::get('bayar-angsuran/{kode_pinjaman}/{pinjaman_angsuran_id}', [PinjamanAngsuranController::class, 'bayar'])->name('pinjaman-angsuran.bayar');
    Route::post('bayar-angsuran/{kode_pinjaman}/{pinjaman_angsuran_id}', [PinjamanAngsuranController::class, 'proses_bayar'])->name('pinjaman-angsuran.proses-bayar');


    // laporan
    Route::get('laporan/pinjaman', [LaporanController::class, 'pinjaman'])->name('laporan.pinjaman.index');
    Route::post('laporan/pinjaman', [LaporanController::class, 'pinjaman_print'])->name('laporan.pinjaman.print');

    // simpanan wajib
    Route::get('simpanan-wajib/tagihan', [SimpananWajibController::class, 'tagihan'])->name('simpanan-wajib.tagihan.index');
    Route::get('simpanan-wajib/tagihan/{id}/bayar', [SimpananWajibController::class, 'tagihan_bayar'])->name('simpanan-wajib.tagihan.bayar');
    Route::post('simpanan-wajib/tagihan/{id}/bayar', [SimpananWajibController::class, 'proses_tagihan_bayar'])->name('simpanan-wajib.tagihan.proses-bayar');

    Route::get('simpanan-wajib/saldo', [SimpananWajibController::class, 'saldo'])->name('simpanan-wajib.saldo.index');

    // simpanan wajib
    Route::get('simpanan-shr/tagihan', [SimpananShrController::class, 'tagihan'])->name('simpanan-shr.tagihan.index');
    Route::get('simpanan-shr/tagihan/{id}/bayar', [SimpananShrController::class, 'tagihan_bayar'])->name('simpanan-shr.tagihan.bayar');
    Route::post('simpanan-shr/tagihan/{id}/bayar', [SimpananShrController::class, 'proses_tagihan_bayar'])->name('simpanan-shr.tagihan.proses-bayar');

    Route::get('simpanan-shr/saldo', [SimpananShrController::class, 'saldo'])->name('simpanan-shr.saldo.index');
    Route::post('simpanan-shr/saldo', [SimpananShrController::class, 'saldo'])->name('simpanan-shr.saldo.filter');
});
