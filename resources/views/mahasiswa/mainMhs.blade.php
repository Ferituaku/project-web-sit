@extends('layout')

@section('contentMhs')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@6.7.1/css/font-awesome.css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #277bff;
        --background-color: #f8f9fa;
        --card-background: #ffffff;
        --text-color: #333333;
        --sidebar-width: 250px;
        --sidebar-width-collapsed: 70px;
        --transition-speed: 0.3s;
    }

    body {
        background-color: var(--background-color);
        color: var(--text-color);
        overflow-x: hidden;
    }

    .sidebar {
        background-color: var(--secondary-color);
        position: fixed;
        color: #ffffff;
        width: var(--sidebar-width);
        transition: width var(--transition-speed);
        z-index: 1000;
    }

    .sidebar.collapsed {
        width: var(--sidebar-width-collapsed) !important;
    }

    .sidebar .nav-link {
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color var(--transition-speed), opacity var(--transition-speed);
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar.collapsed .nav-link span {
        display: none;
    }

    .main-content {
        transition: margin-left var(--transition-speed);
        margin-left: var(--sidebar-width);
    }

    .main-content.expanded {
        margin-left: var(--sidebar-width-collapsed);
    }

    .header {
        transition: margin-left var(--transition-speed);
        margin-left: var(--sidebar-width);
    }

    .header.expanded {
        margin-left: var(--sidebar-width-collapsed);
    }

    .toggle-sidebar {
        position: fixed;
        left: 250px;
        top: 11%;
        background-color: var(--secondary-color);
        color: white;
        border: none;
        padding: 8px;
        cursor: pointer;
        transition: left var(--transition-speed);
        z-index: 1001;
        border-radius: 0 5px 5px 0;
    }

    .toggle-sidebar.collapsed {
        left: var(--sidebar-width-collapsed);
    }

    @media (max-width: 768px) {
        .sidebar {
            width: var(--sidebar-width-collapsed);
        }

        .main-content,
        .header {
            margin-left: var(--sidebar-width-collapsed);
        }

        .toggle-sidebar {
            left: var(--sidebar-width-collapsed);
        }
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<button class="toggle-sidebar">
    <i class="bi bi-chevron-left"></i>
</button>
<div class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar fixed-top vh-100 shadow p-2">
        <div class="d-flex flex-column p-3 h-100">
            <a href="#" class="navbar-brand d-flex align-items-center mb-4 text-white">
                <img src="{{ asset('img/Universitas-Diponegoro-Semarang-Logo.png') }}" alt="logo" style="height: 40px;">
                <span class="fs-5 fw-bold ms-2">SIT Undip</span>
            </a>
            <ul class="nav flex-column mb-auto">
                @foreach([
                ['route' => 'mahasiswa.dashboard', 'icon' => 'speedometer2', 'label' => 'Dashboard'],
                ['route' => 'mahasiswa.jadwal', 'icon' => 'calendar', 'label' => 'Jadwal'],
                ['route' => 'mahasiswa.irs', 'icon' => 'bookmark', 'label' => 'IRS'],
                ['route' => 'mahasiswa.akademikMhs.akademik-base', 'icon' => 'mortarboard', 'label' => 'Akademik'],
                ['route' => 'mahasiswa.biaya', 'icon' => 'wallet2', 'label' => 'Biaya Kuliah'],
                ['route' => 'mahasiswa.herreg', 'icon' => 'file-earmark-text', 'label' => 'Her-Registrasi']
                ] as $item)
                <li class="nav-item mb-2">
                    <a href="{{ route($item['route']) }}" class="nav-link text-light">
                        <i class="bi bi-{{ $item['icon'] }} me-2"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            <div class="mt-auto">
                <a href="{{ route('logout') }}" class="nav-link text-danger d-flex align-items-center">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <span>Log Out</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content flex-grow-1">
        <!-- Header -->
        <header class="header fixed-top bg-white p-3 border-bottom shadow-sm">
            <div class="d-flex justify-content-end align-items-center">
                <div class="dropdown text-end">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2 text-dark">{{ auth()->user()->name }}</span>
                        <img src="{{ asset('img/pakvinsen.jpeg') }}" alt="user" width="32" height="32" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu text-small">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="container-fluid py-2" style="margin-top: 50px;">

            @yield('content')
            @yield('scripts')
        </div>
    </main>
</div>

<script>
    $(document).ready(function() {
        $('.toggle-sidebar').click(function() {
            $('.sidebar').toggleClass('collapsed');
            $('.main-content, .header').toggleClass('expanded');
            $(this).toggleClass('collapsed');
            const icon = $(this).find('i');
            icon.toggleClass('bi-chevron-left bi-chevron-right');
        });

        function checkWidth() {
            if ($(window).width() <= 768) {
                $('.sidebar, .main-content, .header, .toggle-sidebar').addClass('collapsed expanded');
                $('.toggle-sidebar i').removeClass('bi-chevron-left').addClass('bi-chevron-right');
            }
        }
        checkWidth();
        $(window).resize(checkWidth);
    });
</script>
@endsection