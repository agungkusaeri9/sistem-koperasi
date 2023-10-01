<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\MetodePembayaran;
use App\Models\PencairanSimpanan;
use App\Models\Periode;
use App\Models\Pinjaman;
use App\Models\PinjamanAngsuran;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SimpananWajibController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:admin,anggota')->only(['pencairan']);
        $this->middleware('checkRole:anggota')->only(['tagihan', 'tagihan_bayar', 'proses_tagihan_bayar', 'saldo']);
        $this->middleware('checkRole:admin')->only(['index', 'edit', 'update', 'pencairan_create', 'cek_saldo', 'proses_pencairan', 'pencairan_edit', 'pencairan_update', 'pencairan_delete']);
    }

    public function index()
    {
        $status = request('status');
        if ($status === 'semua')
            $items = SimpananAnggota::with(['anggota'])->jenisWajib()->latest()->get();
        elseif ($status)
            $items = SimpananAnggota::where('status_tagihan', $status)->with(['anggota'])->jenisWajib()->latest()->get();
        else
            $items = SimpananAnggota::with(['anggota'])->jenisWajib()->latest()->get();


        return view('pages.simpanan-wajib.index', [
            'title' => 'Simpanan Wajib',
            'items' => $items,
            'status' => $status === 'semua' ? 'semua' : $status
        ]);
    }

    public function tagihan()
    {
        $items = SimpananAnggota::jenisWajib()->ByAnggota()->whereHas('simpanan', function ($simpanan) {
            $simpanan->where('jenis', 'wajib');
        })->latest()->get();

        return view('pages.simpanan-wajib.tagihan', [
            'title' => 'Tagihan Simpanan Wajib',
            'items' => $items
        ]);
    }
    public function tagihan_bayar($id)
    {
        // cek jika simpanan sudah lunas
        $item = SimpananAnggota::jenisWajib()->with('simpanan')->where('id', $id)->firstOrFail();
        if ($item->status_tagihan == 2) {
            return redirect()->back()->with('warning', 'Tagihan tersebut sudah lunas.');
        }
        return view('pages.simpanan-wajib/bayar', [
            'title' => 'Bayar Simpanan Wajib',
            'item' => $item,
            'data_metode_pembayaran' => MetodePembayaran::bySistem()->get()
        ]);
    }

    public function proses_tagihan_bayar(WhatsappService $whatsappService, $id)
    {
        $metode_pembayaran = MetodePembayaran::find(request('metode_pembayaran_id'));
        request()->validate([
            'metode_pembayaran_id' => ['required', 'numeric'],
            'bukti_pembayaran' => [Rule::when($metode_pembayaran->nomor ?? 0 != NULL, ['required', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'])]
        ]);

        DB::beginTransaction();

        try {
            $simpanan_anggota = SimpananAnggota::jenisWajib()->where('id', $id)->first();

            $simpanan_anggota->update([
                'metode_pembayaran_id' => request('metode_pembayaran_id'),
                'bukti_pembayaran' => request()->file('bukti_pembayaran') ? request()->file('bukti_pembayaran')->store('angsuran/bukti-pembayaran', 'public') : NULL,
                'status_tagihan' => 1
            ]);
            DB::commit();

            // kirim notifikasi ke admin
            $whatsappService->admin_bukti_pembayaran_simpanan_wajib($simpanan_anggota->id);
            return redirect()->route('simpanan-wajib.tagihan.index')->with('success', 'Bukti pembayaran berhasil diupload. Dimohon tunggu admin untuk memverifikasi pembayaran.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('simpanan-wajib.tagihan.index')->with('error', $th->getMessage());
        }
    }

    public function saldo()
    {
        $items = SimpananAnggota::jenisWajib()->with('simpanan')->ByAnggota()->where('status_tagihan', 2)->where('status_pencairan', 0)->get();

        $saldo = Simpanan::where('jenis', 'wajib')->whereHas('simpanan_anggota', function ($sa) {
            $sa->where([
                'anggota_id' => auth()->user()->anggota->id,
                'status_tagihan' => 2,
                'status_pencairan' => 0
            ]);
        })->sum('nominal');

        return view('pages.simpanan-wajib.saldo', [
            'title' => 'Informasi Saldo Simpanan Wajib',
            'items' => $items,
            'saldo' => $saldo
        ]);
    }

    public function edit($id)
    {
        $item = SimpananAnggota::jenisWajib()->where('id', $id)->firstOrFail();
        return view('pages.simpanan-wajib.edit', [
            'title' => 'Edit Simpanan Wajib',
            'item' => $item,
            'data_metode_pembayaran' => MetodePembayaran::bySistem()->get()
        ]);
    }

    public function update(WhatsappService $whatsappService, $id)
    {
        request()->validate([
            'status_tagihan' => ['required', 'in:0,1,2']
        ]);

        DB::beginTransaction();

        try {
            $data = request()->only(['metode_pembayaran_id', 'status_tagihan']);
            $item = SimpananAnggota::jenisWajib()->where('id', $id)->firstOrFail();
            $item_2 = SimpananAnggota::jenisWajib()->where('id', $id)->firstOrFail();
            $item->update($data);



            DB::commit();
            if (request('status_tagihan') == 2 && $item_2->status_tagihan != 2) {
                $whatsappService->anggota_verifikasi_simpanan_wajib($item->id);
            }
            return redirect()->route('simpanan-wajib.index')->with('success', 'Simpanan Wajib berhasil diupdate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('simpanan-wajib.index')->with('error', $th->getMessage());
        }
    }

    public function pencairan()
    {
        $items = PencairanSimpanan::where('jenis', 'wajib')->latest()->get();
        return view('pages.simpanan-wajib.pencairan', [
            'title' => 'Pencairan Simpanan Wajib',
            'items' => $items
        ]);
    }

    public function pencairan_create()
    {
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        $data_periode = Periode::latest()->get();
        return view('pages.simpanan-wajib.pencairan-create', [
            'title' => 'Buat Pencairan Simpanan Wajib',
            'data_anggota' => $data_anggota,
            'data_periode' => $data_periode
        ]);
    }


    public function pencairan_proses(WhatsappService $whatsappService)
    {
        request()->validate([
            'metode_pembayaran_id' => ['required', 'numeric'],
            'anggota_id' => ['required']
        ]);

        $anggota_id = request('anggota_id');

        // cek apakah simpanan sudah ada
        $cekPencairan = PencairanSimpanan::jenisWajib()->where('anggota_id', $anggota_id)->count();
        if ($cekPencairan > 0) {
            return redirect()->back()->with('error', 'Pencairan Simpanan wajib anggota tersebut sudah ada.');
        }
        // cek tagihan simpanan wajib dan shr yang belum bayar
        $cekSimpananBelumBayar = SimpananAnggota::where('status_tagihan', '!=', 2)->where('anggota_id', $anggota_id)->count();

        // cek tagihan pinjaman angsuran apakah ada yang belum di bayar
        $cekPinjamanAngsuran = Pinjaman::where([
            'status' => 1,
            'anggota_id' => $anggota_id
        ])->count();

        if ($cekSimpananBelumBayar > 0) {
            return redirect()->back()->with('error', 'Anda masih mempunyai tagihan simpanan wajib/shr yang belum dibayarkan.');
        }

        if ($cekPinjamanAngsuran > 0) {
            return redirect()->back()->with('error', 'Anda masih mempunyai tagihan pinjaman yang belum dibayarkan.');
        }


        DB::beginTransaction();
        try {
            $saldo = Simpanan::whereHas('simpanan_anggota', function ($sa) use ($anggota_id) {
                $sa->jenisWajib()->where([
                    'anggota_id' => $anggota_id,
                    'status_pencairan' => 0,
                    'status_tagihan' => 2
                ]);
            })->sum('nominal');

            if ($saldo < 1) {
                return redirect()->back()->with('error', 'Saldo yang anda miliki tidak mencukupi untuk melakukan pencairan dana simpanan wajib.');
            }

            $pencairan = PencairanSimpanan::create([
                'jenis' => 'wajib',
                'anggota_id' => $anggota_id,
                'nominal' => $saldo,
                'status' => 1,
                'periode_id' => NULL,
                'metode_pembayaran_id' => request('metode_pembayaran_id'),
                'bukti_pencairan' => request()->file('bukti_pencairan') ? request()->file('bukti_pencairan')->store('simpanan-shr/bukti-pencairan', 'public') : NULL
            ]);



            // update simpanan ke sudah dicairkan
            SimpananAnggota::jenisWajib()->where('anggota_id', $anggota_id)->update([
                'status_pencairan' => 1
            ]);
            // non aktifkan anggota
            Anggota::findOrFail($anggota_id)->update([
                'status' => 0
            ]);

            DB::commit();

            // kirim notifikasi ke anggota
            $whatsappService->anggota_pencairan_simpanan_wajib($pencairan->id);

            return redirect()->route('simpanan-wajib.pencairan.index')->with('success', 'Pencairan simpanan wajib berhasil ditambahkan.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function pencairan_delete($id)
    {
        $item = PencairanSimpanan::findOrFail($id);
        if ($item->status == 1) {
            return redirect()->back()->with('error', 'Pencairan Simpanan Wajib tidak bisa dihapus.');
        }
        $item->delete();
        return redirect()->back()->with('success', 'Pencairan Simpanan Wajib berhasil dihapus.');
    }

    public function pencairan_edit($id)
    {
        $item  = PencairanSimpanan::with('anggota')->findOrFail($id);
        $data_metode_pembayaran = MetodePembayaran::where('anggota_id', $item->anggota->id)->get();
        return view('pages.simpanan-wajib.pencairan-edit', [
            'title' => 'Buat Pencairan Simpanan Wajib',
            'item' => $item,
            'data_metode_pembayaran' => $data_metode_pembayaran
        ]);
    }

    public function pencairan_update($id)
    {
        request()->validate([
            'metode_pembayaran_id' => ['required'],
            'status' => ['required', 'in:0,1,2,3']
        ]);

        DB::beginTransaction();
        try {
            $item = PencairanSimpanan::findOrFail($id);

            // jika status gagal => 0, 2, 3
            if ($item->status != 1) {
                // jika status pencairan bukan sukses
                if (request('status') == 1) {
                    // jika pilihan = sukses
                    // update status simpanan di simpanan anggota
                    SimpananAnggota::jenisWajib()->where([
                        'anggota_id' => $item->anggota_id
                    ])->update([
                        'status_pencairan' => 1
                    ]);
                }
            } elseif ($item->status == 1) {
                if (request('status') != 1) {
                    // jika pilihan = sukses
                    // update status simpanan di simpanan anggota
                    SimpananAnggota::jenisWajib()->where([
                        'anggota_id' => $item->anggota_id
                    ])->update([
                        'status_pencairan' => 0
                    ]);
                }
            }

            $item->update([
                'metode_pembayaran_id' => request('metode_pembayaran_id'),
                'status' => request('status'),
                'bukti_pencairan' => request()->file('bukti_pencairan') ? request()->file('bukti_pencairan')->store('simpanan-shr/bukti-pencairan', 'public') : $item->bukti_pencairan
            ]);

            DB::commit();
            return redirect()->route('simpanan-wajib.pencairan.index')->with('success', 'Pencairan Simpanan Wajib berhasil di update');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('simpanan-wajib.pencairan.index')->with('error', $th->getMessage());
        }
    }

    public function cek_saldo()
    {
        if (request()->ajax()) {
            $anggota_id = request('anggota_id');

            if ($anggota_id) {
                $saldo = SimpananAnggota::jenisWajib()
                    ->withSum('simpanan', 'nominal') //
                    ->where('anggota_id', $anggota_id)
                    ->where('status_tagihan', 2)
                    ->where('status_pencairan', 0)
                    ->get();

                return response()->json([
                    'status' => 'success',
                    'saldo' => $saldo->sum('simpanan_sum_nominal') ?? 0
                ]);
            }
        }
    }
}
