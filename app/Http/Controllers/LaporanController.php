<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
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
        ]);
        $fileName = "Laporan pinjaman " . time() . '.pdf';
        // return $pdf->stream();
        return $pdf->download($fileName);
    }
}
