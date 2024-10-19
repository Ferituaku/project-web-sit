@extends('layout')
@section('title', 'Dashboard Dosen')
@section('contentDsn')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
@auth
<div class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar bg-light vh-100 position-fixed shadow p-2 mb-5 bg-body-tertiary rounded" style="width: 250px;">
        <div class="d-flex flex-column p-3 h-100">
            <a href="#" class="navbar-brand d-flex align-items-center mb-4">
                <img src="{{ asset('img/Universitas-Diponegoro-Semarang-Logo.png') }}" alt="logo" class="img-fluid" style="height: 50px; width: 50px;">
                <span class="fs-5 fw-bold ms-2 text-dark">SIT Undip</span>
            </a>
            <!-- Navigation Menu -->
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link active d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('verifikasi')}}" class="nav-link text-muted d-flex align-items-center">
                        <i class="bi bi-file-earmark-check" style="margin-right: 8px;"></i>
                        Verifikasi IRS
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('jadwal')}}" class="nav-link text-muted d-flex align-items-center">
                        <i class="bi bi-calendar me-2"></i>
                        Jadwal
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('konsultasi')}}" class="nav-link text-muted d-flex align-items-center">
                        <i class="bi bi-chat-dots" style="margin-right: 10px;"></i>
                        Konsultasi Mahasiswa
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
    <main class="flex-grow-1" style="margin-left: 285px;">
        <!-- Header -->
        <header class="bg-white p-3 border-bottom fixed-top" style="margin-left: 263px;">
            <div class="d-flex justify-content-end align-items-center">
                <div class="dropdown text-end flex flex-row items-center ms-auto justify-end gap-2">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="text-dark me-2">{{ auth()->user()->name }}</span>
                        <img src="{{ asset('img/budosen.jpg') }}" alt="user" width="32" height="32" class="rounded-circle">
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

        <!-- Page Content -->
        <div class="container-fluid py-4" style="margin-top: 60px; margin-left:10px">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
            {{--<div class="row g-4">
                <!-- User Info Card -->
                <div class="col-md-12">
                    <div class="card h-100" style="border-radius: 1rem;  background: hsla(199, 97%, 66%, 1);
                        background: linear-gradient(180deg, hsla(199, 97%, 66%, 1) 0%, hsla(254, 62%, 49%, 1) 100%);
                        background: -moz-linear-gradient(180deg, hsla(199, 97%, 66%, 1) 0%, hsla(254, 62%, 49%, 1) 100%);
                        background: -webkit-linear-gradient(180deg, hsla(199, 97%, 66%, 1) 0%, hsla(254, 62%, 49%, 1) 100%);
                        ">
                        <div class="card-body d-flex flex-column flex-md-row align-items-center">
                            <div class="mb-3 mb-md-0 me-md-3">
                                <img src="{{ asset('img/budosen.jpg') }}" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
        </div>
        <div class="text-center text-md-start">
            <h4 class="text-white">{{ auth()->user()->name }}</h4>
            <p class="text-white mb-1">{{ auth()->user()->email }}</p>
            <p class="text-white mb-1">NIP: 139945678000</p>
            <p class="text-white mb-1">No. Telp: (021) 765-43533</p>
            <p class="text-white mb-0">Alamat: Pati, Sukolilo</p>
        </div>
</div>
</div>
</div>

<!-- IRS Status Information Card -->
<div class="col-md-8">
    <div class="card" style="border-color:blue; border-radius:1rem; background: rgba(100, 100, 100, 0);">
        <div class="card-body text-center">
            <h3 class="mb-4">Status IRS Mahasiswa</h3>

            <table class="table table-hover">
                ...
            </table>

        </div>
    </div>
</div>

<!-- Statistik -->
<div class="col-md-4">
    <div class="row-sm-2">
        <div class="card mt-2" style="border-color:blue; border-radius:1rem; background: rgba(100, 100, 100, 0);">
            <div class="card-body text-center">
            </div>
        </div>
        <div class="card mt-2" style="border-color:blue; border-radius:1rem; background: rgba(100, 100, 100, 0);">
            <div class="card-body text-center">
            </div>
        </div>
        <div class="card mt-2" style="border-color:blue; border-radius:1rem; background: rgba(100, 100, 100, 0);">
            <div class="card-body text-center">
            </div>
        </div>
    </div>
</div>
</div>--}}
</div>
</main>
</div>

@endauth
@endsection