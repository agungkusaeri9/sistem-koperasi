<?php

namespace App\Http\Controllers;

use App\Models\LamaAngsuran;
use App\Models\MetodePembayaran;
use App\Models\Pinjaman;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PinjamanController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:admin,anggota')->only(['show']);
        $this->middleware('checkRole:anggota')->only(['create', 'store']);
        $this->middleware('checkRole:admin')->only(['update', 'destroy']);
    }

    public function index()
    {
        $status = request('status');
        if ($status == 'semua')
            $items = Pinjaman::whereNotNull('id');
        elseif ($status != NULL)
            $items = Pinjaman::where('status', $status)->whereNotNull('id');
        else
            $items = Pinjaman::whereNotNull('id');

        if (auth()->user()->role !== 'anggota') {
            $data = $items->with('anggota')->latest()->get();
        } else {
            // anggota
            $data = $items->where('anggota_id', auth()->user()->anggota->id)->with('anggota')->latest()->get();
        }

        return view('pages.pinjaman.index', [
            'title' => 'Pinjaman',
            'items' => $data,
            'status' => $status ?? 'semua'
        ]);
    }

    public function create()
    {
        return view('pages.pinjaman.create', [
            'title' => 'Buat Pengangajuan Pinjaman',
            'data_lama_angsuran' => LamaAngsuran::orderBy('durasi', 'ASC')->get(),
            'data_metode_pembayaran' => MetodePembayaran::byAnggota()->get()
        ]);
    }

    public function store(WhatsappService $whatsappService)
    {
        request()->validate([
            'besar_pinjaman' => ['required', 'numeric'],
            'keperluan' => ['required', 'min:5'],
            'lama_angsuran_id' => ['required', 'numeric']
        ]);

        DB::beginTransaction();

        try {
            $besar_pinjaman = request('besar_pinjaman');
            $lama_angsuran = LamaAngsuran::findOrFail(request('lama_angsuran_id'));
            // kalkulasi pinjaman
            $hasil_kalkulasi = Pinjaman::kalkukasiPinjaman($besar_pinjaman, $lama_angsuran->id);
            $kode_baru = Pinjaman::buatKodeBaru();

            // cari mulan bulan sampai akhir
            $tanggal_sekarang = Carbon::now();
            $bulan_mulai = $tanggal_sekarang->addMonth()->month; // Bulan mulai + 1

            // looping lama angsuran
            for ($i = 0; $i < $lama_angsuran->durasi; $i++) {
                $bulan_hitung = ($bulan_mulai + $i) % 12;
                $tahun_angsuran = $tanggal_sekarang->year + floor(($bulan_mulai + $i) / 12);

                // Jika hasil modulus adalah 0, maka bulan adalah Desember
                if ($bulan_hitung === 0) {
                    $bulan_hitung = 12;
                    $tahun_angsuran--;
                }
            }

            // Menghitung tahun sampai berdasarkan bulan akhir
            $bulan_akhir = ($bulan_mulai + $lama_angsuran->durasi - 1) % 12;
            $tahun_sampai = $tanggal_sekarang->year + floor(($bulan_mulai + $lama_angsuran->durasi - 1) / 12);

            // create pinjaman
            $pinjaman = Pinjaman::create([
                'anggota_id' => auth()->user()->anggota->id,
                'kode' => $kode_baru,
                'besar_pinjaman' => $besar_pinjaman,
                'keperluan' => request('keperluan'),
                'lama_angsuran_id' => request('lama_angsuran_id'),
                'potongan_awal' => $hasil_kalkulasi['potongan_awal'],
                'jumlah_diterima' => $hasil_kalkulasi['jumlah_diterima'],
                'angsuran_pokok_bulan' => $hasil_kalkulasi['angsuran_pokok_bulan'],
                'jasa_pinjaman_bulan' => $hasil_kalkulasi['jasa_pinjaman_bulan'],
                'total_jumlah_angsuran_bulan' => $hasil_kalkulasi['total_jumlah_angsuran_bulan'],
                'total_bayar' => $hasil_kalkulasi['total_bayar'],
                'bulan_mulai' => $bulan_mulai,
                'tahun_mulai' => $tahun_angsuran,
                'bulan_sampai' => $bulan_akhir,
                'tahun_sampai' => $tahun_sampai
            ]);


            // kirim notifikasi
            // $whatsappService->admin_submitPinjaman($pinjaman->id);

            DB::commit();
            return redirect()->route('pinjaman.index')->with('success', 'Pengajuan pinjaman berhasil dilakukan. Mohon tunggu beberapa waktu untuk peninjauan admin.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengajuan pinjaman anda ditolak admin.');
        }
    }

    public function show($kode)
    {
        if (auth()->user()->role !== 'anggota') {
            $item = Pinjaman::with(['anggota', 'angsuran', 'lama_angsuran', 'met_pencairan'])->where('kode', $kode)->firstOrFail();
        } else {
            $item = Pinjaman::with(['anggota', 'angsuran', 'lama_angsuran', 'met_pencairan'])->where([
                'kode' => $kode,
                'anggota_id' => auth()->user()->anggota->id
            ])->firstOrFail();
        }
        return view('pages.pinjaman.show', [
            'title' => 'Detail Pinjaman',
            'item' => $item
        ]);
    }


    public function update(WhatsappService $whatsappService, $id)
    {
        request()->validate([
            'status' => ['required', 'numeric']
        ]);


        DB::beginTransaction();
        try {
            $status = request('status');
            $item = Pinjaman::findOrFail($id);

            if ($status == 1) {
                $tanggal_sekarang = Carbon::now();
                $bulan_mulai = $tanggal_sekarang->addMonth()->month; // Bulan mulai + 1
                $lama_angsuran = $item->lama_angsuran->durasi;

                // looping lama angsuran
                for ($i = 0; $i < $lama_angsuran; $i++) {
                    $bulan_hitung = ($bulan_mulai + $i) % 12;
                    $tahun_angsuran = $tanggal_sekarang->year + floor(($bulan_mulai + $i) / 12);

                    // Jika hasil modulus adalah 0, maka bulan adalah Desember
                    if ($bulan_hitung === 0) {
                        $bulan_hitung = 12;
                        $tahun_angsuran--;
                    }

                    // buat angsuran
                    $item->angsuran()->create([
                        'bulan' => $bulan_hitung,
                        'tahun' => $tahun_angsuran,
                        'uuid' => \Str::uuid()
                    ]);
                }

                // Menghitung tahun sampai berdasarkan bulan akhir
                $bulan_akhir = ($bulan_mulai + $lama_angsuran - 1) % 12;
                $tahun_sampai = $tanggal_sekarang->year + floor(($bulan_mulai + $lama_angsuran - 1) / 12);

                // Jika hasil modulus adalah 0, maka bulan adalah Desember
                if ($bulan_akhir === 0) {
                    $bulan_akhir = 12;
                    $tahun_sampai--;
                }


                // update
                $item->tanggal_diterima = Carbon::now()->translatedFormat('Y-m-d');
                $item->diterima_oleh = auth()->id();
                $item->bulan_mulai = $bulan_mulai;
                $item->tahun_mulai = $tanggal_sekarang->year;
                $item->bulan_sampai = $bulan_akhir;
                $item->tahun_sampai = $tahun_sampai;
            }
            $item->status = $status;
            $item->save();

            // notifikasi ke anggota
            // if ($status == 1) {
            //     // disetujui
            //     $whatsappService->anggota_pinjaman_disetujui($item->id);
            // } elseif ($status == 3) {
            //     // ditolak
            //     $whatsappService->anggota_pinjaman_ditolak($item->id);
            // } elseif ($status == 2) {
            //     // lunas/selesai
            //     $whatsappService->anggota_pinjaman_selesai($item->id);
            // }

            DB::commit();

            return redirect()->back()->with('success', 'Pinjaman berhasil diupdate.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Pinjaman gagal diupdate.');
        }
    }

    public function destroy($id)
    {
        $item = Pinjaman::findOrFail($id);
        // cek pinjaman jika success
        if ($item->status == 1 || $item->status == 2) {
            return redirect()->back()->with('warning', 'Pinjaman tidak bisa dihapus!');
        }
        $item->delete();
        return redirect()->back()->with('success', 'Pinjaman berhasil dihapus.');
    }

    public function export_pdf($kode)
    {
        $item = Pinjaman::with(['anggota', 'lama_angsuran'])->where('kode', $kode)->firstOrFail();
        $pdf = Pdf::loadView('pages.pinjaman.export-pdf', [
            'item' => $item
        ]);
        $fileName = "Pinjaman-" . $item->kode . '.pdf';
        // return $pdf->stream();
        return $pdf->download($fileName);
    }

    public function kalkulasi()
    {
        request()->validate([
            'besar_pinjaman' => ['required', 'numeric'],
            'lama_angsuran_id' => ['required', 'numeric']
        ]);


        // kalkulasi pinjaman
        $kalkukasi = Pinjaman::kalkukasiPinjaman(request('besar_pinjaman'), request('lama_angsuran_id'));

        return response()->json($kalkukasi);
    }

    public function set_status_potongan_awal()
    {
        request()->validate([
            'id' => ['required'],
            'status' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $pinjaman = Pinjaman::findOrFail(request('id'));

            $pinjaman->update([
                'status_potongan_awal' => request('status')
            ]);
            DB::commit();

            return redirect()->route('pinjaman.show', $pinjaman->kode)->with('success', 'Status Potongan Awal berhasil diupdate.');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->route('pinjaman.show', $pinjaman->kode)->with('error', $th->getMessage());
        }
    }
}
