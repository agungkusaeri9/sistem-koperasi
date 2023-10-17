<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
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
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        return view('pages.laporan.pinjaman.index', [
            'title' => 'Laporan Pinjaman',
            'status' => 'semua',
            'data_anggota' => $data_anggota
        ]);
    }

    public function pinjaman_print()
    {
        $anggota_id = request('anggota_id');
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

        if ($status !== 'semua')
            $items->where('status', $status);

        if ($anggota_id)
            $items->where('anggota_id', $anggota_id);

        $data = $items->latest()->get();


        if (count($data) < 1) {
            return redirect()->route('laporan.pinjaman.index')->with('warning', 'Data pinjaman tidak ditemukan.');
        }

        $tanggal_awal = Carbon::parse($tanggal_awal)->format('d F Y');
        $tanggal_sampai = Carbon::parse($tanggal_sampai)->format('d F Y');
        $anggota = Anggota::find($anggota_id);
        $pdf = Pdf::loadView('pages.laporan.pinjaman.print', [
            'items' => $data,
            'status' => $status,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_sampai' => $tanggal_sampai,
            'anggota' => $anggota
        ])->setPaper('A4', 'landscape');
        $fileName = "Laporan pinjaman " . time() . '.pdf';
        // return $pdf->stream();
        return $pdf->download($fileName);
    }

    public function simpanan_shr()
    {
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        $data_periode = Periode::latest()->get();
        return view('pages.laporan.simpanan-shr.index', [
            'title' => 'Laporan Simpanan SHR',
            'status' => 'semua',
            'data_anggota' => $data_anggota,
            'data_periode' => $data_periode
        ]);
    }

    public function simpanan_shr_print()
    {
        $periode_id = request('periode_id');
        $anggota_id = request('anggota_id');


        $items = Simpanan::jenisShr()->with(['anggota']);
        if ($periode_id) {
            $items->where('periode_id', $periode_id);
        }

        if ($anggota_id) {
            $items->where('anggota_id', $anggota_id);
        }

        $data = $items->latest()->get();


        if (count($data) < 1) {
            return redirect()->route('laporan.simpanan-shr.index')->with('warning', 'Data Simpanan SHR tidak ditemukan.');
        }

        $anggota = Anggota::find($anggota_id);
        $periode = periode::find($periode_id);
        $pdf = Pdf::loadView('pages.laporan.simpanan-shr.print', [
            'items' => $data,
            'periode' => $periode,
            'anggota' => $anggota
        ]);
        $fileName = "Laporan Simpanan SHR " . time() . '.pdf';
        // return $pdf->stream();
        return $pdf->download($fileName);
    }

    public function simpanan_wajib()
    {
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        return view('pages.laporan.simpanan-wajib.index', [
            'title' => 'Laporan Simpanan wajib',
            'status' => 'semua',
            'data_anggota' => $data_anggota
        ]);
    }

    public function simpanan_wajib_print()
    {
        $anggota_id = request('anggota_id');

        $items = Simpanan::jenisWajib()->with(['anggota']);
        if ($anggota_id) {
            $items->where('anggota_id', $anggota_id);
        }

        $data = $items->latest()->get();

        if (count($data) < 1) {
            return redirect()->route('laporan.simpanan-wajib.index')->with('warning', 'Data Simpanan wajib tidak ditemukan.');
        }

        $anggota = Anggota::find($anggota_id);
        $pdf = Pdf::loadView('pages.laporan.simpanan-wajib.print', [
            'items' => $data,
            'anggota' => $anggota
        ]);
        $fileName = "Laporan Simpanan wajib " . time() . '.pdf';
        // return $pdf->stream();
        return $pdf->download($fileName);
    }
}
