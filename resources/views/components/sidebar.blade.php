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
                <a class="nav-link" data-toggle="collapse" href="#trk_pinjaman" aria-expanded="false"
                    aria-controls="trk_pinjaman">
                    <span class="material-symbols-outlined mr-2">
                        savings
                    </span>
                    <span class="menu-title">Transaksi Pinjaman</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="trk_pinjaman">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pinjaman.index') }}"> Pinjaman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pinjaman-angsuran.index') }}"> Angsuran Pinjaman </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#trk_simpanan" aria-expanded="false"
                    aria-controls="trk_simpanan">
                    <span class="material-symbols-outlined mr-2">
                        savings
                    </span>
                    <span class="menu-title">Transaksi Simpanan</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="trk_simpanan">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-wajib.index') }}"> Simpanan Wajib</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-shr.index') }}"> Simpanan SHR </a>
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
                    <span class="menu-title">Pencairan Dana</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="simpanan_shr">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pencairan-simpanan-wajib.index') }}"> Simpanan Wajib</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pencairan-simpanan-shr.index') }}"> Simpanan SHR </a>
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
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('laporan.simpanan-wajib.index') }}"> Simpanan Wajib </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('laporan.simpanan-shr.index') }}"> Simpanan SHR </a>
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
                <a class="nav-link" href="{{ route('pinjaman.index') }}">
                    <span class="material-symbols-outlined mr-2">
                        request_page
                    </span>
                    <span class="menu-title">Riwayat Pinjaman</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#info_saldo" aria-expanded="false"
                    aria-controls="info_saldo">
                    <span class="material-symbols-outlined mr-2">
                        savings
                    </span>
                    <span class="menu-title">Informasi Saldo</sup></span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="info_saldo">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-wajib.saldo.index') }}"> Simpanan Wajib
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simpanan-shr.saldo.index') }}"> Simpanan SHR
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="{{ route('panduan.index') }}">
                <span class="material-symbols-outlined mr-2">
                    help
                </span>
                <span class="menu-title">Panduan Pengguna</span>
            </a>
        </li>
    </ul>
</nav>
