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
    <table class="styled-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal Pengajuan</th>
                <th>Kode</th>
                <th>Nama Anggota</th>
                <th>Status</th>
                <th>Besar Pinjaman</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td style="text-align: center">{{ formatTanggalBulanTahun($item->created_at) }}</td>
                    <td style="text-align: center">{{ $item->kode }}</td>
                    <td style="text-align: center">{{ $item->anggota->nama }}</td>
                    <td style="text-align: center">{!! $item->status() !!}</td>
                    <td style="text-align: right">Rp {{ number_format($item->besar_pinjaman, 0, '.', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" style="text-align: center;font-weight:bold">
                    Total
                </td>
                <td style="text-align: right;font-weight:bold">
                    {{ formatRupiah($items->sum('besar_pinjaman')) }}
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
