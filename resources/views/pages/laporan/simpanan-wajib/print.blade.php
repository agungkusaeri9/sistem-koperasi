<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Simpanan Wajib</title>
    <style>
        body {
            font-size: 12px;
        }

        table {
            width: 100%;
        }

        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #101a18;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .styled-table tbody tr.active-row,
        tr.active-row {
            font-weight: bold;
            color: #009879;

        }
    </style>
</head>

<body>
    <h2 style="text-align: center">Laporan Simpanan Wajib</h2>
    <table class="tb-info">
        @if ($bulan)
            <tr>
                <td style="text-align:left;width:100px">Bulan</td>
                <td style="width:10px"> : </td>
                <td>
                    {{ konversiBulan($bulan) }}
                </td>
            </tr>
        @endif
        @if ($tahun)
            <tr>
                <td style="text-align:left;width:100px">Tahun</td>
                <td style="width:10px"> : </td>
                <td>
                    {{ $tahun }}
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
                        @if ($status == 0)
                            Belum Bayar
                        @elseif($status == 1)
                            Menunggu Verifikasi
                        @elseif($status == 2)
                            Lunas
                        @endif
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
    <table class="styled-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Nominal</th>
                <th>Anggota</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $simpanan_anggota)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td style="text-align: center">{{ konversiBulan($simpanan_anggota->simpanan->bulan) }}</td>
                    <td style="text-align: center">{{ $simpanan_anggota->simpanan->tahun }}</td>
                    <td style="text-align: center">{{ formatRupiah($simpanan_anggota->simpanan->nominal) }}</td>
                    <td style="text-align: center">{{ $simpanan_anggota->anggota->nama }}</td>
                    <td style="text-align: center">
                        {{ $simpanan_anggota->metode_pembayaran_id ? $simpanan_anggota->metode_pembayaran->getFull() : 'Tidak Ada' }}
                    </td>

                    <td style="text-align: center">{!! $simpanan_anggota->status_tagihan() !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
