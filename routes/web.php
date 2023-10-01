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
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\SimpananShrController;
use App\Http\Controllers\SimpananWajibController;
use App\Http\Controllers\TagihanSimpananController;
use App\Models\JenisSimpanan;
use App\Models\MetodePembayaran;
use App\Models\Simpanan;
use App\Services\WhatsappService;
use GuzzleHttp\Client;
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

    // pinjaman angsuran
    Route::post('pinjaman-angsuran/{id}', [PinjamanAngsuranController::class, 'update'])->name('pinjaman-angsuran.update');
    Route::get('bayar-angsuran/{kode_pinjaman}/{pinjaman_angsuran_id}', [PinjamanAngsuranController::class, 'bayar'])->name('pinjaman-angsuran.bayar');
    Route::post('bayar-angsuran/{kode_pinjaman}/{pinjaman_angsuran_id}', [PinjamanAngsuranController::class, 'proses_bayar'])->name('pinjaman-angsuran.proses-bayar');


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
    Route::resource('simpanan-wajib', SimpananWajibController::class)->except('create', 'store', 'show');
    Route::get('simpanan-wajib', [SimpananWajibController::class, 'index'])->name('simpanan-wajib.index');
    Route::post('simpanan-wajib', [SimpananWajibController::class, 'index'])->name('simpanan-wajib.filter');
    Route::get('simpanan-wajib/tagihan', [SimpananWajibController::class, 'tagihan'])->name('simpanan-wajib.tagihan.index');
    Route::get('simpanan-wajib/tagihan/{id}/bayar', [SimpananWajibController::class, 'tagihan_bayar'])->name('simpanan-wajib.tagihan.bayar');
    Route::post('simpanan-wajib/tagihan/{id}/bayar', [SimpananWajibController::class, 'proses_tagihan_bayar'])->name('simpanan-wajib.tagihan.proses-bayar');

    // pencairan simpanan Wajib
    // Route::get('simpanan-wajib/pengajuan-pencairan', [SimpananWajibController::class, 'pengajuan_pencairan'])->name('simpanan-wajib.pengajuan-pencairan.index');
    Route::post('simpanan-wajib/pencairan', [SimpananWajibController::class, 'pencairan_proses'])->name('simpanan-wajib.pencairan.proses');
    // Route::post('simpanan-wajib/pengajuan-pencairan/set-batal', [SimpananWajibController::class, 'proses_batal'])->name('simpanan-wajib.pengajuan-pencairan.batal');

    Route::get('simpanan-wajib/pencairan/{id}/edit', [SimpananWajibController::class, 'pencairan_edit'])->name('simpanan-wajib.pencairan.edit');
    Route::patch('simpanan-wajib/pencairan/{id}/edit', [SimpananWajibController::class, 'pencairan_update'])->name('simpanan-wajib.pencairan.update');

    Route::get('simpanan-wajib/pencairan', [SimpananWajibController::class, 'pencairan'])->name('simpanan-wajib.pencairan.index');
    Route::get('simpanan-wajib/pencairan/create', [SimpananWajibController::class, 'pencairan_create'])->name('simpanan-wajib.pencairan.create');
    Route::post('simpanan-wajib/pencairan/{id}/set-status', [SimpananWajibController::class, 'pencairan_update_status'])->name('simpanan-wajib.pencairan.set-status');
    Route::delete('simpanan-wajib/pencairan/{id}', [SimpananWajibController::class, 'pencairan_delete'])->name('simpanan-wajib.pencairan.destroy');

    // saldo simpanan wajib
    Route::get('simpanan-wajib/saldo', [SimpananWajibController::class, 'saldo'])->name('simpanan-wajib.saldo.index');
    Route::post('simpanan-wajib/cek-saldo', [SimpananWajibController::class, 'cek_saldo'])->name('simpanan-wajib.cek-saldo');

    // simpanan shr
    Route::resource('simpanan-shr', SimpananShrController::class)->except('create', 'store', 'show');
    Route::post('simpanan-shr', [SimpananShrController::class, 'index'])->name('simpanan-shr.filter');
    Route::get('simpanan-shr/tagihan', [SimpananShrController::class, 'tagihan'])->name('simpanan-shr.tagihan.index');
    Route::get('simpanan-shr/tagihan/{id}/bayar', [SimpananShrController::class, 'tagihan_bayar'])->name('simpanan-shr.tagihan.bayar');
    Route::post('simpanan-shr/tagihan/{id}/bayar', [SimpananShrController::class, 'proses_tagihan_bayar'])->name('simpanan-shr.tagihan.proses-bayar');

    Route::get('simpanan-shr/saldo', [SimpananShrController::class, 'saldo'])->name('simpanan-shr.saldo.index');
    Route::post('simpanan-shr/saldo', [SimpananShrController::class, 'saldo'])->name('simpanan-shr.saldo.filter');

    Route::get('simpanan-shr/pencairan', [SimpananShrController::class, 'pencairan'])->name('simpanan-shr.pencairan.index');
    Route::post('simpanan-shr/pencairan', [SimpananShrController::class, 'pencairan'])->name('simpanan-shr.pencairan.filter');
    Route::patch('simpanan-shr/pencairan/{id}/edit', [SimpananShrController::class, 'pencairan_update'])->name('simpanan-shr.pencairan.update');
    Route::get('simpanan-shr/pencairan/{id}/edit', [SimpananShrController::class, 'pencairan_edit'])->name('simpanan-shr.pencairan.edit');
    Route::get('simpanan-shr/pencairan/create', [SimpananShrController::class, 'pencairan_create'])->name('simpanan-shr.pencairan.create');
    Route::delete('simpanan-shr/pencairan/{id}', [SimpananShrController::class, 'pencairan_delete'])->name('simpanan-shr.pencairan.destroy');


    // cek saldo anggota berdasarkan periode
    Route::post('simpanan-shr/cek-saldo', [SimpananShrController::class, 'cek_saldo'])->name('simpanan-shr.cek-saldo');
    Route::post('simpanan-shr/pencairan-proses', [SimpananShrController::class, 'proses_pencairan'])->name('simpanan-shr.pencairan.proses');
});

Route::get('/test', function () {
    try {
        $curl = curl_init();
        $token = "vfn4vejxTccWCHfoqRDLUc2GsPr6X6FhGKLGL88i9tzeysrhsJ9gcqmWe1Esozcv";
        $phone = "+6282122018025";
        $message = "test get";
        curl_setopt($curl, CURLOPT_URL, "https://jogja.wablas.com/api/send-message?phone=$phone&message=$message&token=$token");
        $result = curl_exec($curl);
        curl_close($curl);
        echo "<pre>";
        print_r($result);
    } catch (\Throwable $th) {
        throw $th;
        Log("Error kirim whatsapp ke  081919956872");
    }
});
