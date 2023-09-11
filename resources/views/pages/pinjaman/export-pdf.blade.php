<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pinjaman {{ $item->kode }}</title>
    <style>
        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        body {
            font-size: 15px;
        }
    </style>
</head>

<body>
    <h4 class="text-center">
        KOPERASI SEJAHTERA KARYA BERSAMA <br>
        FORMULIR PERMOHONAN PEMINJAMAN DANA KOPERASI
    </h4>

    <p>Kepada Yth, <br>
        Pengurus Koperasi Sejahtera Karya Bersama <br>
        Di tempat</p>
    <p>Dengan hormat,</p>

    <table style="width:100%">
        <tr>
            <td colspan="3" class="text-left">Yang bertanda tangan dibawah ini, saya :</td>
        </tr>
        <tr>
            <td class="text-left" style="width:80px">Nama</td>
            <td style="width:20px"> : </td>
            <td>{{ $item->anggota->nama }}</td>
        </tr>
        <tr>
            <td class="text-left" style="width:80px">Jabatan</td>
            <td style="width:20px"> : </td>
            <td>{{ $item->anggota->jabatan->nama }}</td>
        </tr>
        <tr>
            <td class="text-left" style="width:80px">Alamat</td>
            <td style="width:20px"> : </td>
            <td>{{ $item->anggota->alamat }}</td>
        </tr>
        <tr>
            <td class="text-left" style="width:80px">No. HP</td>
            <td style="width:20px"> : </td>
            <td>{{ $item->anggota->nomor_telepon }}</td>
        </tr>
    </table>

    <p>Dengan ini mengajukan permohonan peminjaman dana koperasi sejahtera karya bersama :</p>
    <table style="width:100%">
        <tr>
            <td style="width:10px">1. </td>
            <td class="text-left" style="width:240px">Besar Pinjaman</td>
            <td style="width:20px"> : </td>
            <td>{{ formatRupiah($item->besar_pinjaman) }}</td>
        </tr>
        <tr>
            <td style="width:10px">2. </td>
            <td class="text-left" style="width:240px">Untuk Keperluan</td>
            <td style="width:20px"> : </td>
            <td>{{ $item->keperluan }}</td>
        </tr>
        <tr>
            <td style="width:10px">3. </td>
            <td class="text-left" style="width:240px">Angsuran Selama</td>
            <td style="width:20px"> : </td>
            <td>{{ $item->lama_angsuran->durasi . ' kali' }}</td>
        </tr>
        <tr>
            <td style="width:10px">4. </td>
            <td class="text-left" style="width:240px">Mulai Bulan</td>
            <td style="width:20px"> : </td>
            <td>{{ konversiBulan($item->bulan_mulai) . ' Tahun ' . $item->tahun_mulai }}</td>
        </tr>
        <tr>
            <td style="width:10px"></td>
            <td class="text-left" style="width:240px">Sampai dengan Bulan</td>
            <td style="width:20px"> : </td>
            <td>{{ konversiBulan($item->bulan_sampai) . ' Tahun ' . $item->tahun_sampai }}</td>
        </tr>
        <tr>
            <td style="width:10px">5. </td>
            <td class="text-left" style="width:240px">Besar Potongan Awal
                ({{ $item->lama_angsuran->potongan_awal_persen . '%' }})</td>
            <td style="width:20px"> : </td>
            <td>{{ formatRupiah($item->potongan_awal) }}</td>
        </tr>
        <tr>
            <td style="width:10px">6</td>
            <td class="text-left" style="width:240px">Jumlah yang diterima</td>
            <td style="width:20px"> : </td>
            <td>{{ formatRupiah($item->jumlah_diterima) }}</td>
        </tr>
        <tr>
            <td style="width:10px">7. </td>
            <td class="text-left" style="width:240px">Besar angsuran pokok/bulan</td>
            <td style="width:20px"> : </td>
            <td>{{ formatRupiah($item->angsuran_pokok_bulan) }}</td>
        </tr>
        <tr>
            <td style="width:10px">8. </td>
            <td class="text-left" style="width:240px">Besar jasa pinjaman/bulan
                ({{ $item->lama_angsuran->jasa_pinjaman_bulan_persen . '%' }})</td>
            <td style="width:20px"> : </td>
            <td>{{ formatRupiah($item->jasa_pinjaman_bulan) }}</td>
        </tr>
        <tr>
            <td style="width:10px">9. </td>
            <td class="text-left" style="width:240px">Besar Jumlah Angsuran/bulan</td>
            <td style="width:20px"> : </td>
            <td>{{ formatRupiah($item->total_jumlah_angsuran_bulan) }}</td>
        </tr>
    </table>
    <p>Demikian surat permohonan pinjaman dana ini saya isi dengan sebenar-benarnya. Apabila ada kesalahan didalam
        mengisi formulir peminjaman ini saya bersedia menerima konsekuensi dari pengurus koperasi sejahtera karya
        bersama.</p>

    <table style="width:100%">
        <tr>
            <td style="width:50%">
                <p class="text-left">
                    Pihak keluarga Pemohon
                </p>
                <br>
                <br>
                <br>
                <p class="text-left">(.....................................)</p>
            </td>
            <td style="width:50%">
                <p class="text-left">
                    Tegalwaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    <br>
                    Pemohon;
                </p>
                <br>
                <br>
                <br>
                <p class="text-left">{{ $item->anggota->nama }}</p>
            </td>
        </tr>
        <tr>
            <td style="width:50%">
                <p class="text-left">
                    Mengetahui;
                    <br>
                    Kepala Sekolah
                </p>
                <br>
                <br>
                <br>
                <p class="text-left"> <u style="font-weight:bold;">Tuti Haryati, S.E </u>
                    <br>
                    <span>NIP.</span>
                </p>

            </td>
            <td style="width:50%">
                <p class="text-left">
                    Menyetujui;
                    <br>
                    Ketua Koperasi;
                </p>
                <br>
                <br>
                <br>
                <p class="text-left"><u style="font-weight:bold;">Didin Haerudin, S.pd</u>
                    <br>
                    <span>NIP.</span>
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
