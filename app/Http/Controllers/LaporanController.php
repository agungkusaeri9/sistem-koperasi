<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:admin');
    }


    public function pinjaman()
    {
        return view('pages.laporan.pinjaman.index', [
            'title' => 'Laporan Pinjaman',
            'status' => 'semua'
        ]);
    }

    public function pinjaman_print()
    {
        $tanggal_awal = request('tanggal_awal');
        $tanggal_sampai = request('tanggal_sampai');
        $status = request('status');

        if ($tanggal_awal && $tanggal_sampai) {
            // jika keduanya di isi
            $items = Pinjaman::whereBetween('created_at', [$tanggal_awal, $tanggal_sampai]);
        } elseif ($tanggal_awal && !$tanggal_sampai) {
            $items = Pinjaman::whereDate('created_at', $tanggal_awal);
        } else {
            $items = Pinjaman::whereNotNull('id');
        }

        if ($status === 'semua')
            $data = $items->latest()->get();
        else
            $data = $items->where('status', $status)->latest()->get();


        if (count($data) < 1) {
            return redirect()->route('laporan.pinjaman.index')->with('warning', 'Data pinjaman tidak ditemukan.');
        }

        $tanggal_awal = Carbon::parse($tanggal_awal)->format('d F Y');
        $tanggal_sampai = Carbon::parse($tanggal_sampai)->format('d F Y');
        $pdf = Pdf::loadView('pages.laporan.pinjaman.print', [
            'items' => $data,
            'status' => $status,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_sampai' => $tanggal_sampai
        ])->setPaper('A4', 'landscape');
        $fileName = "Laporan pinjaman " . time() . '.pdf';
        // return $pdf->stream();
        return $pdf->download($fileName);
    }

    public function simpanan_shr()
    {
        return view('pages.laporan.simpanan-shr.index', [
            'title' => 'Laporan Simpanan SHR',
            'status' => 'semua',
            'data_bulan' => Periode::getBulan(),
            'data_tahun' => Periode::getTahun()
        ]);
    }

    public function simpanan_shr_print()
    {
        $bulan = request('bulan');
        $tahun = request('tahun');
        $status = request('status');

        $items = SimpananAnggota::jenisShr()->with(['anggota']);
        if ($bulan) {
            $items = $items->whereHas('simpanan', function ($simpanan) use ($bulan) {
                $simpanan->where('bulan', $bulan);
            });
        }

        if ($tahun) {
            $items = $items->whereHas('simpanan', function ($simpanan) use ($tahun) {
                $simpanan->where('tahun', $tahun);
            });
        }

        if ($status === 'semua')
            $items = $items;
        else
            $items = $items->where('status_tagihan', $status);

        $data = $items->latest()->get();

        if (count($data) < 1) {
            return redirect()->route('laporan.simpanan-shr.index')->with('warning', 'Data Simpanan SHR tidak ditemukan.');
        }

        $pdf = Pdf::loadView('pages.laporan.simpanan-shr.print', [
            'items' => $data,
            'status' => $status,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
        $fileName = "Laporan Simpanan SHR " . time() . '.pdf';
        // return $pdf->stream();
        return $pdf->download($fileName);
    }

    public function simpanan_wajib()
    {
        return view('pages.laporan.simpanan-wajib.index', [
            'title' => 'Laporan Simpanan wajib',
            'status' => 'semua',
            'data_bulan' => Periode::getBulan(),
            'data_tahun' => Periode::getTahun()
        ]);
    }

    public function simpanan_wajib_print()
    {
        $bulan = request('bulan');
        $tahun = request('tahun');
        $status = request('status');

        $items = SimpananAnggota::jenisWajib()->with(['anggota']);
        if ($bulan) {
            $items = $items->whereHas('simpanan', function ($simpanan) use ($bulan) {
                $simpanan->where('bulan', $bulan);
            });
        }

        if ($tahun) {
            $items = $items->whereHas('simpanan', function ($simpanan) use ($tahun) {
                $simpanan->where('tahun', $tahun);
            });
        }

        if ($status === 'semua')
            $items = $items;
        else
            $items = $items->where('status_tagihan', $status);

        $data = $items->latest()->get();

        if (count($data) < 1) {
            return redirect()->route('laporan.simpanan-wajib.index')->with('warning', 'Data Simpanan wajib tidak ditemukan.');
        }

        $pdf = Pdf::loadView('pages.laporan.simpanan-wajib.print', [
            'items' => $data,
            'status' => $status,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
        $fileName = "Laporan Simpanan wajib " . time() . '.pdf';
        // return $pdf->stream();
        return $pdf->download($fileName);
    }
}
