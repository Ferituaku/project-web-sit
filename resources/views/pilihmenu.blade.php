@extends('layout')
@section('title', 'Menu Pilih')
@section('contentpilih')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .bg-custom {
        background-image: url('/img/back-ground-log-sign.jpg');
        background-size: cover;
        background-position: center;
    }

    .card-custom {
        background: rgba(67, 126, 255, 0.7);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 1rem;
    }

    .btn-custom {
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>

@auth
<section class="vh-100 bg-custom d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card card-custom text-white" style="border:none; border-radius: 1rem; backdrop-filter: blur(10px); background: rgba(67, 126, 255, 0.5);">
                    <div class="card-body text-center p-5">
                        <h1 class="mb-4">Selamat Datang!</h1>
                        <p class="mb-4">Anda ingin Masuk Sebagai:</p>
                        <div class="d-grid gap-3">
                            <a href="{{ route('dosen.dashboard') }}" class="btn btn-outline-light btn-lg btn-custom">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Menu Dosen Wali
                            </a>
                            @if(Auth::user()->role == 'dekan')
                            <a href="{{ route('dekan.dashboard') }}" class="btn btn-outline-light btn-lg btn-custom">
                                <i class="fas fa-user-tie me-2"></i>Menu Dekan
                            </a>
                            @elseif(Auth::user()->role == 'kaprodi')
                            <a href="{{ route('kaprodi.dashboard') }}" class="btn btn-outline-light btn-lg btn-custom">
                                <i class="fas fa-user-graduate me-2"></i>Menu Kaprodi
                            </a>
                            @endif
                        </div>
                        <div class="mt-5">
                            <a href="/logout" class="btn btn-danger btn-custom">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>