<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#master_data" aria-expanded="false"
                aria-controls="master_data">
                <i class="icon-folder menu-icon"></i>
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
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('anggota.index') }}">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Anggota</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('pengaturan.index') }}">
                <i class="icon-cog menu-icon"></i>
                <span class="menu-title">Pengaturan</span>
            </a>
        </li>
    </ul>
</nav>
