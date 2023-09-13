<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $admin_count = [
            'jumlah_anggota' => Anggota::count(),
            'jumlah_pinjaman' => Pinjaman::count(),
            'jumlah_pinjaman_menunggu_persetujuan' => Pinjaman::where('status', 0)->count(),
            'jumlah_pinjaman_selesai' => Pinjaman::where('status', 2)->count()
        ];
        $pinjaman_terakhir = Pinjaman::with(['anggota'])->latest()->limit(10)->get();
        return view('pages.dashboard', [
            'title' => 'Dashboard',
            'admin_count' => $admin_count,
            'pinjaman_terakhir' => $pinjaman_terakhir
        ]);
    }
}
