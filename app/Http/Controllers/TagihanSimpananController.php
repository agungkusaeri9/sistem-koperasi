<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Pengaturan;
use App\Models\Periode;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TagihanSimpananController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:admin');
    }

    public function index()
    {
        $jenis = request('jenis');
        if ($jenis) {
            $items = Simpanan::where('jenis', $jenis)->latest()->get();
        } else {
            $items = Simpanan::orderBy('jenis', 'ASC')->latest()->get();
        }
        return view('pages.tagihan-simpanan.index', [
            'title' => 'Tagihan Simpanan',
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('pages.tagihan-simpanan.create', [
            'title' => 'Tambah Tagihan Simpanan',
            'data_periode' => Periode::get(),
            'data_bulan' => Periode::getBulan(),
            'data_tahun' => Periode::getTahun()
        ]);
    }

    public function store(WhatsappService $whatsappService)
    {
        request()->validate([
            'jenis' => ['required'],
            'bulan' => ['required', 'numeric'],
            'tahun' => ['required', 'numeric'],
            'periode_id' => [Rule::when(request('jenis') === 'shr', ['required', 'numeric'])]
        ]);

        DB::beginTransaction();
        try {

            // cek apakah ada yang sama
            $cekTagihan = Simpanan::where([
                'jenis' => request('jenis'),
                'bulan' => request('bulan'),
                'tahun' => request('tahun'),
                'periode_id' => request('periode_id')
            ])->count();

            if ($cekTagihan > 0) {
                return  redirect()->back()->with('error', 'Tagihan tersebut sudah ada di database.');
            }
            $pengaturan = Pengaturan::first();
            $data = request()->only(['jenis', 'bulan', 'tahun', 'periode_id']);
            if (request('jenis') == 'wajib')
                $data['nominal'] = $pengaturan->nominal_simpanan_wajib;
            else
                $data['nominal'] = $pengaturan->nominal_simpanan_shr;

            $simpanan = Simpanan::create($data);

            // buatkan tagihan ke anggota
            $data_anggota = Anggota::whereHas('user', function ($user) {
                $user->where('is_active', 1);
            })->get();

            foreach ($data_anggota as $anggota) {

                // cek jika sudah ada simpanan
                $simpananCek = Simpanan::where([
                    'anggota_id' => $anggota->id,
                    'simpanan_id' => $simpanan->id
                ])->count();

                if ($simpananCek < 1) {
                    Simpanan::create([
                        'anggota_id' => $anggota->id,
                        'simpanan_id' => $simpanan->id,
                        'status' => 0
                    ]);

                    // kirim notifikasi
                    $whatsappService->anggota_tagihan_simpanan($simpanan->id, $anggota->id);
                }
            }

            DB::commit();
            return redirect()->route('tagihan-simpanan.index')->with('success', 'Tagihan Simpanan berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tagihan-simpanan.index')->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Simpanan::findOrFail($id);
        return view('pages.tagihan-simpanan.edit', [
            'title' => 'Edit Tagihan Simpanan',
            'item' => $item,
            'data_periode' => Periode::get(),
            'data_bulan' => Periode::getBulan(),
            'data_tahun' => Periode::getTahun()
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'jenis' => ['required'],
            'bulan' => ['required', 'numeric'],
            'tahun' => ['required', 'numeric'],
            'periode_id' => [Rule::when(request('jenis') === 'shr', ['required', 'numeric'])]
        ]);

        DB::beginTransaction();
        try {
            $item = Simpanan::findOrFail($id);

            $cekTagihan = Simpanan::where([
                'jenis' => request('jenis'),
                'bulan' => request('bulan'),
                'tahun' => request('tahun'),
                'periode_id' => request('periode_id')
            ])->where('id', '!=', $id)->count();

            if ($cekTagihan > 0) {
                return  redirect()->back()->with('error', 'Tagihan tersebut sudah ada di database.');
            }
            $pengaturan = Pengaturan::first();
            $data = request()->only(['jenis', 'bulan', 'tahun', 'periode_id']);
            if (request('jenis') == 'wajib')
                $data['nominal'] = $pengaturan->nominal_simpanan_wajib;
            else
                $data['nominal'] = $pengaturan->nominal_simpanan_shr;

            $item->update($data);
            DB::commit();
            return redirect()->route('tagihan-simpanan.index')->with('success', 'Tagihan Simpanan berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tagihan-simpanan.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Simpanan::findOrFail($id);

        DB::beginTransaction();
        try {
            $item->delete();
            DB::commit();
            return redirect()->route('tagihan-simpanan.index')->with('success', 'Tagihan Simpanan berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tagihan-simpanan.index')->with('error', $th->getMessage());
        }
    }
}
