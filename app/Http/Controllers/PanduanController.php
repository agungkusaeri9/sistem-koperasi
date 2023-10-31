<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanduanController extends Controller
{
    public function index()
    {
        $title = 'Panduan Pengguna';
        if (auth()->user()->role === 'anggota') {
            return view('pages.panduan.anggota', compact('title'));
        } else {
            return view('pages.panduan.admin', compact('title'));
        }
    }
}
