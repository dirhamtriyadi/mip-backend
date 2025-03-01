<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('adminlte') }}/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('adminlte') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('profile.index') }}" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @canany(['jam-kerja.index', 'jam-kerja.create', 'jam-kerja.edit', 'jam-kerja.delete',
                    'hari-libur.index', 'hari-libur.create', 'hari-libur.edit', 'hari-libur.delete', 'bank.index',
                    'bank.create', 'bank.edit', 'bank.delete', 'nasabah.index', 'nasabah.create', 'nasabah.edit',
                    'nasabah.delete'])
                    <li
                        class="nav-item {{ Route::is('work-schedules.*') | Route::is('annual-holidays.*') | Route::is('banks.*') | Route::is('customers.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Route::is('work-schedules.*') | Route::is('annual-holidays.*') | Route::is('banks.*') | Route::is('customers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-database"></i>
                            <p>
                                Master Data
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['jam-kerja.index', 'jam-kerja.create', 'jam-kerja.edit', 'jam-kerja.delete'])
                                <li class="nav-item">
                                    <a href="{{ route('work-schedules.index') }}"
                                        class="nav-link {{ Route::is('work-schedules.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Jam dan Hari Kerja</p>
                                    </a>
                                </li>
                            @endcan
                            @canany(['hari-libur.index', 'hari-libur.create', 'hari-libur.edit', 'hari-libur.delete'])
                                <li class="nav-item">
                                    <a href="{{ route('annual-holidays.index') }}"
                                        class="nav-link {{ Route::is('annual-holidays.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Hari Libur</p>
                                    </a>
                                </li>
                            @endcan
                            @canany(['bank.index', 'bank.create', 'bank.edit', 'bank.delete'])
                                <li class="nav-item">
                                    <a href="{{ route('banks.index') }}"
                                        class="nav-link {{ Route::is('banks.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Bank</p>
                                    </a>
                                </li>
                            @endcan
                            @canany(['nasabah.index', 'nasabah.create', 'nasabah.edit', 'nasabah.delete'])
                                <li class="nav-item">
                                    <a href="{{ route('customers.index') }}"
                                        class="nav-link {{ Route::is('customers.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nasabah</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @canany(['kehadiran.index', 'kehadiran.create', 'kehadiran.edit', 'kehadiran.delete', 'cuti.index',
                    'cuti.create', 'cuti.edit', 'cuti.delete'])
                    <li
                        class="nav-item {{ Route::is('attendances.*') | Route::is('leaves.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Route::is('attendances.*') | Route::is('leaves.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>
                                Kehadiran
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['kehadiran.index', 'kehadiran.create', 'kehadiran.edit', 'kehadiran.delete'])
                                <li class="nav-item">
                                    <a href="{{ route('attendances.index') }}"
                                        class="nav-link {{ Route::is('attendances.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kehadiran</p>
                                    </a>
                                </li>
                            @endcan
                            @canany(['cuti.index', 'cuti.create', 'cuti.edit', 'cuti.delete'])
                                <li class="nav-item">
                                    <a href="{{ route('leaves.index') }}"
                                        class="nav-link {{ Route::is('leaves.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cuti</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @canany(['penagihan.index', 'penagihan.create', 'penagihan.edit', 'penagihan.delete'])
                    <li class="nav-item {{ Route::is('customer-billings.*') ? 'active' : '' }}">
                        <a href="{{ route('customer-billings.index') }}"
                            class="nav-link {{ Route::is('customer-billings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>
                                Penagihan
                            </p>
                        </a>
                    </li>
                @endcan
                <li
                    class="nav-item {{ Route::is('officer-reports.*') | Route::is('attendance-reports.*') | Route::is('customer-billing-reports.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Route::is('officer-reports.*') | Route::is('attendance-reports.*') | Route::is('customer-billing-reports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @canany(['laporan-petugas.index', 'laporan-petugas.create', 'laporan-petugas.edit',
                            'laporan-petugas.delete'])
                            <li class="nav-item">
                                <a href="{{ route('officer-reports.index') }}"
                                    class="nav-link {{ Route::is('officer-reports.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Petugas</p>
                                </a>
                            </li>
                        @endcan
                        @canany(['laporan-kehadiran.index', 'laporan-kehadiran.create', 'laporan-kehadiran.edit',
                            'laporan-kehadiran.delete'])
                            <li class="nav-item">
                                <a href="{{ route('attendance-reports.index') }}"
                                    class="nav-link {{ Route::is('attendance-reports.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Kehadiran</p>
                                </a>
                            </li>
                        @endcan
                        @canany(['laporan-penagihan.index', 'laporan-penagihan.create', 'laporan-penagihan.edit',
                            'laporan-penagihan.delete'])
                            <li class="nav-item">
                                <a href="{{ route('customer-billing-reports.index') }}"
                                    class="nav-link {{ Route::is('customer-billing-reports.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Laporan Penagihan
                                    </p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @canany(['role.index', 'role.create', 'role.edit', 'role.delete'])
                    <li class="nav-item">
                        <a href="{{ route('roles.index') }}" class="nav-link {{ Route::is('roles.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>
                                Roles
                            </p>
                        </a>
                    </li>
                @endcan
                @canany(['user.index', 'user.create', 'user.edit', 'user.delete'])
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ Route::is('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
