<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-size: 12px;
        }

        table {
            width: 100%;
        }


        .styled-table {
            border-collapse: collapse;
            /* margin: 25px 0; */
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;

        }

        .styled-table2 {
            border-collapse: collapse;
            /* margin: 25px 0; */
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;

        }

        .tb-info {
            border-collapse: collapse;
            margin-bottom: 20px
        }

        .styled-table thead tr {
            background-color: #101a18;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 2px 3px;
        }

        /* .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        } */

        /* .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        } */

        /* .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        } */

        /* .styled-table tbody tr.active-row,
        tr.active-row {
            font-weight: bold;
            color: #009879;

        } */
    </style>
</head>

<body>
    <h2 style="text-align: center">Laporan Pinjaman</h2>
    <table class="tb-info">
        @if ($tanggal_awal)
            <tr>
                <td style="text-align:left;width:100px">Dari Tanggal</td>
                <td style="width:10px"> : </td>
                <td>
                    {{ $tanggal_awal }}
                </td>
            </tr>
        @endif
        @if ($tanggal_sampai)
            <tr>
                <td style="text-align:left;width:100px">Sampai Tanggal</td>
                <td style="width:10px"> : </td>
                <td>
                    {{ $tanggal_sampai }}
                </td>
            </tr>
        @endif
        @if ($status && $status !== 'semua')
            <tr>
                <td style="text-align:left;width:100px">Status</td>
                <td style="width:10px"> : </td>
                <td>
                    @if ($status === 'semua')
                        Semua
                    @else
                        {!! $items->first()->status() !!}
                    @endif
                </td>
            </tr>
        @endif
        <tr>
            <td style="text-align:left;width:80px">Tanggal Cetak</td>
            <td style="width:10px"> : </td>
            <td>{{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
        </tr>
    </table>
    <table class="styled-table" border="1">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal Pengajuan</th>
                <th>Kode</th>
                <th>Nama Anggota</th>
                <th>NIP</th>
                <th>Lama Pinjaman</th>
                <th>Besar Pinjaman</th>
                <th>Potongan Awal</th>
                <th>Total Jasa Pinjaman</th>
                <th>Jumlah Diterima</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td style="text-align: center">{{ formatTanggalBulanTahun($item->created_at) }}</td>
                    <td style="text-align: center">{{ $item->kode }}</td>
                    <td style="text-align: center">{{ $item->anggota->nama }}</td>
                    <td style="text-align: center">{{ $item->anggota->nip }}</td>
                    <td style="text-align: center">{{ $item->lama_angsuran->durasi . ' bulan' }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->besar_pinjaman, 0, '.', '.') }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->potongan_awal, 0, '.', '.') }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->totalJasaPinjaman(), 0, '.', '.') }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->jumlah_diterima, 0, '.', '.') }}</td>
                    <td style="text-align: center">{!! $item->status() !!}</td>
                </tr>
                <tr>
                    <td colspan="11">
                        <table class="styled-table2">
                            <tr>
                                <th colspan="7" rowspan="{{ $item->angsuran->count() + 6 }}">Detail Tagihan</th>
                                <th>Jenis Tagihan</th>
                                <th>Jatuh Tempo</th>
                                <th>Nominal</th>
                                <th>Status Tagihan</th>
                            </tr>
                            @foreach ($item->angsuran as $key => $angsuran)
                                <tr>

                                    <td>Angsuran Bulanan</td>
                                    <td>{{ formatTanggal($item->tanggal_diterima) . ' ' . konversiBulan($angsuran->bulan) . ' ' . $angsuran->tahun }}
                                    </td>
                                    <td>{{ formatRupiah($item->total_jumlah_angsuran_bulan) }}</td>
                                    <td>
                                        {!! $angsuran->status() !!}
                                    </td>
                                </tr>
                            @endforeach
                            @if ($item->status == 1)
                                <tr>
                                    <th colspan="2" style="text-align: left">Tagihan ADM
                                        ({{ $item->lama_angsuran->potongan_awal_persen . '%' }})</th>
                                    <td style="text-align: center"> - </td>
                                    <td> {!! $item->statusTagihanPotonganAwal() !!}</td>

                                </tr>
                            @endif
                            <tr>
                                <th colspan="3" style="text-align: left">Sisa Angsuran</th>
                                <td>{{ $item->sisaAngsuranBulan() . ' bulan' }}</td>
                            </tr>
                            <tr>
                                <th colspan="3" style="text-align: left">Total Bayar</th>
                                <td>{{ formatRupiah($item->total_bayar) }}</td>
                            </tr>
                            <tr>
                                <th colspan="3" style="text-align: left">Terbayar</th>
                                <td>{{ formatRupiah($item->tagihanSudahTerbayar()) }}</td>
                            </tr>
                            <tr>
                                <th colspan="3" style="text-align: left">Sisa</th>
                                <td>{{ formatRupiah($item->tagihanBelumTerbayar()) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
