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
use App\Http\Controllers\PencairanSimpananShrController;
use App\Http\Controllers\PencairanSimpananWajibController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PinjamanAngsuranController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\SimpananShrController;
use App\Http\Controllers\SimpananWajibController;
use App\Http\Controllers\TagihanSimpananController;
use App\Models\JenisSimpanan;
use App\Models\MetodePembayaran;
use App\Models\Simpanan;
use App\Services\WhatsappService;
use GuzzleHttp\Client;
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

Route::redirect('/', '/login', 301);

Auth::routes();

Route::middleware(['auth', 'is_active'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // pegawai
    Route::resource('pegawai', PegawaiController::class)->except('show');

    // metode-pembayaran
    Route::post('metode-pembayarans/get-json-by-anggota', [MetodePembayaranController::class, 'get_json_by_anggota'])->name('metode-pembayaran.get-json.by-anggota');
    Route::resource('metode-pembayaran', MetodePembayaranController::class)->except('show');

    // jabatan
    Route::resource('jabatan', JabatanController::class)->except('show');

    // agama
    Route::resource('agama', AgamaController::class)->except('show');
    // periode
    Route::resource('periode', PeriodeController::class)->except('show');

    // anggota
    Route::get('anggota/{id}/json', [AnggotaController::class, 'detail_json'])->name('anggota.detail-json');
    Route::resource('anggota', AnggotaController::class);

    // pengaturan
    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // lama-angsuran
    Route::resource('lama-angsuran', LamaAngsuranController::class);

    // tagihan-simpanan
    Route::post('tagihan-simpanan', [TagihanSimpananController::class, 'index'])->name('tagihan-simpanan.filter');
    Route::resource('tagihan-simpanan', TagihanSimpananController::class)->except(['show', 'store']);
    Route::post('tagihan-simpanan/create', [TagihanSimpananController::class, 'store'])->name('tagihan-simpanan.store');

    // pinjaman
    Route::post('pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.filter');
    Route::resource('pinjaman', PinjamanController::class)->except('store');
    Route::post('pinjaman/create', [PinjamanController::class, 'store'])->name('pinjaman.store');
    Route::post('pinjaman/export-pdf/{kode}', [PinjamanController::class, 'export_pdf'])->name('pinjaman.export-pdf');

    // kalkukasi pinjaman
    Route::post('pinjaman-kalkulasi', [PinjamanController::class, 'kalkulasi'])->name('pinjaman.kalkulasi');

    // update status potongan angsuran
    Route::post('pinjaman/set-status-potongan-awal', [PinjamanController::class, 'set_status_potongan_awal'])->name('pinjaman.set-status-potongan-awal');

    // pinjaman angsuran
    Route::get('angsuran-pinjaman', [PinjamanAngsuranController::class, 'index'])->name('pinjaman-angsuran.index');
    Route::post('angsuran-pinjaman', [PinjamanAngsuranController::class, 'index'])->name('pinjaman-angsuran.filter');
    Route::get('pinjaman-angsuran/{uuid}', [PinjamanAngsuranController::class, 'edit'])->name('pinjaman-angsuran.edit');
    Route::patch('pinjaman-angsuran/{uuid}', [PinjamanAngsuranController::class, 'update'])->name('pinjaman-angsuran.update');


    // laporan pinjaman
    Route::get('laporan/pinjaman', [LaporanController::class, 'pinjaman'])->name('laporan.pinjaman.index');
    Route::post('laporan/pinjaman', [LaporanController::class, 'pinjaman_print'])->name('laporan.pinjaman.print');

    // laporan simpanan shr
    Route::get('laporan/simpanan-shr', [LaporanController::class, 'simpanan_shr'])->name('laporan.simpanan-shr.index');
    Route::post('laporan/simpanan-shr', [LaporanController::class, 'simpanan_shr_print'])->name('laporan.simpanan-shr.print');

    // laporan simpanan wajib
    Route::get('laporan/simpanan-wajib', [LaporanController::class, 'simpanan_wajib'])->name('laporan.simpanan-wajib.index');
    Route::post('laporan/simpanan-wajib', [LaporanController::class, 'simpanan_wajib_print'])->name('laporan.simpanan-wajib.print');

    // simpanan wajib
    Route::resource('simpanan-wajib', SimpananWajibController::class)->except(['show', 'store']);
    Route::post('simpanan-wajib/create', [SimpananWajibController::class, 'store'])->name('simpanan-wajib.store');
    Route::get('simpanan-wajib', [SimpananWajibController::class, 'index'])->name('simpanan-wajib.index');
    Route::post('simpanan-wajib', [SimpananWajibController::class, 'index'])->name('simpanan-wajib.filter');

    // saldo simpanan wajib
    Route::get('simpanan-wajib/saldo', [SimpananWajibController::class, 'saldo'])->name('simpanan-wajib.saldo.index');
    Route::post('simpanan-wajib/cek-saldo', [SimpananWajibController::class, 'cek_saldo'])->name('simpanan-wajib.cek-saldo');

    // cek nominal simpanan
    Route::post('periode/cek-nominal', [PeriodeController::class, 'cekNominalSimpanan'])->name('periode.cek-nominal-simpanan');

    // simpanan shr
    Route::resource('simpanan-shr', SimpananShrController::class)->except('store', 'show');
    Route::post('simpanan-shr/create', [SimpananShrController::class, 'store'])->name('simpanan-shr.store');
    Route::post('simpanan-shr', [SimpananShrController::class, 'index'])->name('simpanan-shr.filter');

    // saldo simpanan shr
    Route::get('simpanan-shr/saldo', [SimpananShrController::class, 'saldo'])->name('simpanan-shr.saldo.index');
    Route::post('simpanan-shr/saldo', [SimpananShrController::class, 'saldo'])->name('simpanan-shr.saldo.filter');

    // pencairan dana simpanan wajib
    Route::resource('pencairan-simpanan-wajib', PencairanSimpananWajibController::class)->except(['edit', 'update', 'destroy', 'show']);

    // pencairan dana simpanan shr
    Route::post('pencairan-simpanan-shr', [PencairanSimpananShrController::class, 'index'])->name('pencairan-simpanan-shr.filter');
    Route::post('pencairan-simpanan-shr/create', [PencairanSimpananShrController::class, 'store'])->name('pencairan-simpanan-shr.store');
    Route::resource('pencairan-simpanan-shr', PencairanSimpananShrController::class)->except(['edit', 'update', 'destroy', 'show', 'store']);


    // cek saldo shr anggota berdasarkan periode
    Route::post('simpanan-shr/cek-saldo', [SimpananShrController::class, 'cek_saldo'])->name('simpanan-shr.cek-saldo');
    Route::post('simpanan-shr/pencairan-proses', [SimpananShrController::class, 'proses_pencairan'])->name('simpanan-shr.pencairan.proses');
});
