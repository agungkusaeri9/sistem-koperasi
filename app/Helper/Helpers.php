<?php

use Carbon\Carbon;

function konversiBulan($nomorBulan)
{
    switch ($nomorBulan) {
        case 1:
            return "Januari";
        case 2:
            return "Februari";
        case 3:
            return "Maret";
        case 4:
            return "April";
        case 5:
            return "Mei";
        case 6:
            return "Juni";
        case 7:
            return "Juli";
        case 8:
            return "Agustus";
        case 9:
            return "September";
        case 10:
            return "Oktober";
        case 11:
            return "November";
        case 12:
            return "Desember";
        default:
            return "Bulan tidak valid";
    }
}

function formatRupiah($nominal)
{
    if ($nominal)
        return "Rp " . number_format($nominal, 0, '.', '.');
    else
        return "Rp. 0";
}

function formatTanggalBulanTahun($tanggal)
{
    if ($tanggal) {
        $carbon = Carbon::parse($tanggal);
        return $carbon->translatedFormat('d-m-Y');
    } else {
        return "-";
    }
}


function isLoginAnggota()
{
    if (auth()->user()->role === 'anggota') {
        return true;
    } else {
        return false;
    }
}


function isLoginNotAnggota()
{
    if (auth()->user()->role !== 'anggota') {
        return true;
    } else {
        return false;
    }
}
