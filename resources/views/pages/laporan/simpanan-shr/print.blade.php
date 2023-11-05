<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Simpanan SHR</title>
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
    </style>
</head>

<body>
    <h2 style="text-align: center">Laporan Simpanan SHR</h2>
    <table class="tb-info">
        @if ($periode)
            <tr>
                <td style="text-align:left;width:100px">Periode</td>
                <td style="width:10px"> : </td>
                <td>
                    {{ $periode->periode() }}
                </td>
            </tr>
        @endif
        @if ($anggota)
            <tr>
                <td style="text-align:left;width:100px">Nama Anggota</td>
                <td style="width:10px"> : </td>
                <td>
                    {{ $anggota->nama }}
                </td>
            </tr>
        @endif
        @if ($anggota)
            <tr>
                <td style="text-align:left;width:100px">NIP</td>
                <td style="width:10px"> : </td>
                <td>
                    {{ $anggota->nip }}
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
                <th>Anggota</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Metode Pembayaran</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $simpanan)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td style="text-align: center">{{ $simpanan->anggota->nama }}</td>
                    <td style="text-align: center">{{ konversiBulan($simpanan->bulan) }}</td>
                    <td style="text-align: center">{{ $simpanan->tahun }}</td>
                    <td style="text-align: center">
                        {{ $simpanan->metode_pembayaran_id ? $simpanan->metode_pembayaran->getFull() : 'Tidak Ada' }}
                    </td>
                    <td style="text-align: center">{{ formatRupiah($simpanan->nominal) }}</td>

                    <td style="text-align: center">{!! $simpanan->status() !!}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="5" class="text-center font-weight-bold">Total</th>
                <th class="text-left">{{ formatRupiah($items->sum('nominal')) }}</th>
                <th></th>
            </tr>
        </tbody>
    </table>
</body>

</html>
