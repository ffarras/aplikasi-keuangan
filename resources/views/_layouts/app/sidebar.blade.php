<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-primary">
                <li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('laporan') ? 'active' : '' }}">
                    <a href="{{ route('laporan') }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('pemasukan') ? 'active' : '' }}">
                    <a href="{{ route('pemasukan.index') }}">
                        <i class="fas fa-money-bill text-blue"></i>
                        <p>Pemasukan</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('pengeluaran') ? 'active' : '' }}">
                    <a href="{{ route('pengeluaran.index') }}">
                        <i class="fas fa-money-bill-wave text-blue"></i>
                        <p>Pengeluaran</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('hutang') ? 'active' : '' }}">
                    <a href="{{ route('hutang.index') }}">
                        <i class="fas fa-money-check-alt"></i>
                        <p>Daftar Reimburse</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('account') ? 'active' : '' }}">
                    <a href="{{ route('account.index') }}">
                        <i class="fas fa-credit-card text-blue"></i>
                        <p>Account</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('user') ? 'active' : Request::is('pegawai') ? 'active' :  Request::is('kategori') ? 'active' : '' }} submenu">
                    <a data-toggle="collapse" href="#admin" class="collapsed" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        <p>Admin</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('user') ? 'show' : Request::is('pegawai') ? 'show' : Request::is('kategori') ? 'show' : '' }}" id="admin">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('user') ? 'active' : '' }}">
                                <a href="{{ route('user.index') }}">
                                    <span class="sub-item">
                                        Data Admin
                                    </span>
                                </a>
                            </li>
                            <li class="{{ Request::is('pegawai') ? 'active' : '' }}">
                                <a href="{{ route('pegawai.index') }}" class="active">
                                    <span class="sub-item">
                                        Data Pegawai
                                    </span>
                                </a>
                            </li>
                            <li class="{{ Request::is('kategori') ? 'active' : '' }}">
                                <a href="{{ route('kategori.index') }}">
                                    <span class="sub-item">
                                        Data Kategori
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->