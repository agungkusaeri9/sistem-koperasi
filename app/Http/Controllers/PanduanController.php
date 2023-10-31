<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanduanController extends Controller
{
    public function index()
    {
        $title = 'Panduan Pengguna';
        if (auth()->user()->role === 'anggota') {
            return view('pages.panduan.anggota.index', compact('title'));
        } else {
            return view('pages.panduan.admin.index', compact('title'));
        }
    }

    public function master_data_pegawai()
    {
        $title = 'Panduan Manajemen Pegawai';
        return view('pages.panduan.admin.master-data-pegawai', compact('title'));
    }

    public function master_data_metode_pembayaran()
    {
        $title = 'Panduan Manajemen Metode Pembayaran';
        return view('pages.panduan.admin.master-data-metode-pembayaran', compact('title'));
    }

    public function master_data_jabatan()
    {
        $title = 'Panduan Manajemen Jabatan';
        return view('pages.panduan.admin.master-data-jabatan', compact('title'));
    }

    public function master_data_agama()
    {
        $title = 'Panduan Manajemen Agama';
        return view('pages.panduan.admin.master-data-agama', compact('title'));
    }

    public function master_data_periode()
    {
        $title = 'Panduan Manajemen Periode';
        return view('pages.panduan.admin.master-data-periode', compact('title'));
    }

    public function master_data_lama_angsuran()
    {
        $title = 'Panduan Manajemen Lama Angsuran';
        return view('pages.panduan.admin.master-data-lama-angsuran', compact('title'));
    }


    public function pinjaman()
    {
        $title = 'Panduan Manajemen Pinjaman';
        return view('pages.panduan.admin.pinjaman', compact('title'));
    }


    public function dashboard_anggota()
    {
        $title = 'Panduan Dashboard';
        return view('pages.panduan.anggota.dashboard', compact('title'));
    }

    public function pinjaman_anggota()
    {
        $title = 'Panduan Pinjaman';
        return view('pages.panduan.anggota.pinjaman', compact('title'));
    }
    public function simpanan_wajib_anggota()
    {
        $title = 'Panduan Simpanan Wajib';
        return view('pages.panduan.anggota.simpanan-wajib', compact('title'));
    }

    public function simpanan_shr_anggota()
    {
        $title = 'Panduan Simpanan Hari Raya';
        return view('pages.panduan.anggota.simpanan-shr', compact('title'));
    }
}
