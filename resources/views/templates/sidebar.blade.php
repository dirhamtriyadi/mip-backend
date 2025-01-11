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
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
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
                <li class="nav-item {{ Route::is('work-schedules.*') | Route::is('annual-holidays.*') | Route::is('banks.*') | Route::is('customers.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::is('work-schedules.*') | Route::is('annual-holidays.*') | Route::is('banks.*') | Route::is('customers.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Master Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('work-schedules.index') }}" class="nav-link {{ Route::is('work-schedules.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jam dan Hari Kerja</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('annual-holidays.index') }}" class="nav-link {{ Route::is('annual-holidays.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hari Libur</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('banks.index') }}" class="nav-link {{ Route::is('banks.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bank</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customers.index') }}" class="nav-link {{ Route::is('customers.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nasabah</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Route::is('attendances.*') | Route::is('attendance-reports.*') | Route::is('leaves.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::is('attendances.*') | Route::is('attendance-reports.*') | Route::is('leaves.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Absen
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('attendances.index') }}" class="nav-link {{ Route::is('attendances.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Absen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendance-reports.index') }}" class="nav-link {{ Route::is('attendance-reports.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laporan Absen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('leaves.index') }}" class="nav-link {{ Route::is('leaves.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cuti</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Route::is('billings.*') ? 'active' : '' }}">
                    <a href="{{ route('billings.index') }}" class="nav-link {{ Route::is('billings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Penagihan
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('billing-reports.*') ? 'active' : '' }}">
                    <a href="{{ route('billing-reports.index') }}" class="nav-link {{ Route::is('billing-reports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>
                            Laporan Penagihan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ Route::is('roles.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            Roles
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ Route::is('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
