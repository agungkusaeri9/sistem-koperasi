<?php

namespace App\View\Components;

use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (auth()->user()->role === 'anggota') {
            $tagihan_simpanan_wajib = SimpananAnggota::jenisWajib()->byAnggota()->whereIn('status_tagihan', [0, 1])->count();
            $tagihan_simpanan_shr = SimpananAnggota::whereHas('simpanan', function ($simpanan) {
                $simpanan->whereHas('periode', function ($periode) {
                    $periode->where('status', 1);
                });
            })->jenisShr()->byAnggota()->whereIn('status_tagihan', [0, 1])->count();
        }
        return view('components.sidebar', [
            'tagihan_simpanan_wajib' => $tagihan_simpanan_wajib ?? 0,
            'tagihan_simpanan_shr' => $tagihan_simpanan_shr ?? 0
        ]);
    }
}
