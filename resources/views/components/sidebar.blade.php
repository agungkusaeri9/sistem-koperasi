<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined mr-2">
                    dashboard
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @if (auth()->user()->role !== 'anggota')
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#transaksi" aria-expanded="false"
                    aria-controls="transaksi">
                    <span class="material-symbols-outlined mr-2">
                        currency_exchange
                    </span>
                    <span class="menu-title">Pinjaman</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="transaksi">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pinjaman.index') }}"> Pinjaman </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tagihan-simpanan.index') }}">
                    <span class="material-symbols-outlined mr-2">
                        request_quote
                    </span>
                    <span class="menu-title">Tagihan Simpanan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#simpanan_wajib" aria-expanded="false"
                    aria-controls="simpanan_wajib">
                    <span class="material-symbols-outlined mr-2">
                        savings
                    </span>
                    <span class="menu-title">Simpanan Wajib</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="simpanan_wajib">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-wajib.index') }}"> Simpanan </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-wajib.pencairan.index') }}"> Pencairan
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#simpanan_shr" aria-expanded="false"
                    aria-controls="simpanan_shr">
                    <span class="material-symbols-outlined mr-2">
                        savings
                    </span>
                    <span class="menu-title">Simpanan SHR</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="simpanan_shr">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-shr.index') }}"> Simpanan </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-shr.pencairan.index') }}"> Pencairan </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#laporan" aria-expanded="false"
                    aria-controls="laporan">
                    <span class="material-symbols-outlined mr-2">
                        description
                    </span>
                    <span class="menu-title">Laporan</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="laporan">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('laporan.pinjaman.index') }}"> Pinjaman </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#master_data" aria-expanded="false"
                    aria-controls="master_data">
                    <span class="material-symbols-outlined mr-2">
                        table
                    </span>
                    <span class="menu-title">Master Data</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="master_data">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pegawai.index') }}"> Pegawai </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('metode-pembayaran.index') }}">
                                Metode Pembayaran </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('jabatan.index') }}">
                                Jabatan </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('agama.index') }}">
                                Agama </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('periode.index') }}">
                                Periode </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lama-angsuran.index') }}">
                                Lama Angsuran </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('anggota.index') }}">
                    <span class="material-symbols-outlined mr-2">
                        group
                    </span>
                    <span class="menu-title">Anggota</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('pengaturan.index') }}">
                    <span class="material-symbols-outlined mr-2">
                        settings
                    </span>
                    <span class="menu-title">Pengaturan</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('pinjaman.create') }}">
                    <span class="material-symbols-outlined mr-2">
                        receipt
                    </span>
                    <span class="menu-title">Pengajuan Pinjaman</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#simpanan_wajib" aria-expanded="false"
                    aria-controls="simpanan_wajib">
                    <span class="material-symbols-outlined mr-2">
                        savings
                    </span>
                    <span class="menu-title">Simpanan Wajib <sup
                            class="">{{ $tagihan_simpanan_wajib }}</sup></span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="simpanan_wajib">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-wajib.tagihan.index') }}"> Tagihan </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-wajib.saldo.index') }}"> Informasi Saldo
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#simpanan_shr" aria-expanded="false"
                    aria-controls="simpanan_shr">
                    <span class="material-symbols-outlined mr-2">
                        savings
                    </span>
                    <span class="menu-title">Simpanan SHR <sup
                            class="">{{ $tagihan_simpanan_shr }}</sup></span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="simpanan_shr">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-shr.tagihan.index') }}"> Tagihan </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-shr.saldo.index') }}"> Informasi Saldo </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-shr.pencairan.index') }}"> Riwayat
                                Pencairan </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('pinjaman.index') }}">
                    <span class="material-symbols-outlined mr-2">
                        request_page
                    </span>
                    <span class="menu-title">Riwayat Pinjaman</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('metode-pembayaran.index') }}">
                    <span class="material-symbols-outlined mr-2">
                        payment
                    </span>
                    <span class="menu-title">Metode Pencairan</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
