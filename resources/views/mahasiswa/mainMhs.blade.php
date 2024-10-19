@extends('layout')

@section('contentMhs')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #004cbaa8;
        --background-color: #f8f9fa;
        --card-background: #ffffff;
        --text-color: #333333;
        --sidebar-width: 250px;
    }

    body {
        background-color: var(--background-color);
        color: var(--text-color);
    }

    .sidebar {
        background-color: var(--secondary-color);
        color: #ffffff;
    }

    .sidebar .nav-link {
        color: #ffffff;
        transition: background-color 0.3s;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .card {
        background-color: var(--card-background);
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>


<div class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar fixed-top vh-100 position-fixed shadow p-2 mb-5" style="width: var(--sidebar-width);">
        <div class="d-flex flex-column p-3 h-100">
            <a href="#" class="navbar-brand d-flex align-items-center mb-4 text-white">
                <img src="{{ asset('img/Universitas-Diponegoro-Semarang-Logo.png') }}" alt="logo" class="img-fluid" style="height: 40px; width: 40px;">
                <span class="fs-5 fw-bold ms-2">SIT Undip</span>
            </a>
            <!-- Navigation Menu -->
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="{{route('mahasiswa.dashboard')}}" class="nav-link active d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('mahasiswa.jadwal')}}" class="nav-link text-light d-flex align-items-center">
                        <i class="bi bi-calendar me-2"></i>
                        Jadwal
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('mahasiswa.kulon')}}" class="nav-link text-light d-flex align-items-center">
                        <i class="bi bi-laptop me-2"></i>
                        Kuliah Online
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('mahasiswa.akademisi')}}" class="nav-link text-light d-flex align-items-center">
                        <i class="bi bi-mortarboard me-2"></i>
                        Akademisi
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('mahasiswa.biaya')}}" class="nav-link text-light d-flex align-items-center">
                        <i class="bi bi-wallet2 me-2"></i>
                        Biaya Kuliah
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('mahasiswa.herreg')}}" class="nav-link text-light d-flex align-items-center">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Her-Registrasi
                    </a>
                </li>
            </ul>
            <!-- Logout -->
            <div class="mt-auto">
                <a href="{{ route('logout') }}" class="nav-link text-danger d-flex align-items-center">
                    <i class="bi bi-box-arrow-right me-2"></i> Log Out
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1" style="margin-left: 260px;">
        <!-- Header -->
        <header class="fixed-top bg-white p-3 border-bottom shadow-sm" style="margin-left: 257px;">
            <div class="d-flex justify-content-end align-items-center">
                <div class="dropdown text-end">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2 text-dark">{{ auth()->user()->name }}</span>
                        <img src="{{ asset('img/pakvinsen.jpeg') }}" alt="user" width="32" height="32" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser">
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

        @yield('content')

    </main>
</div>
<script></script>

@endsection