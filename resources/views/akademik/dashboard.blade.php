@extends('akademik.mainAkd')
@section('title', 'Dashboard Akademik')

@section('content')


<!-- Main Content -->
<div class="container-fluid py-4">
    <!-- Breadcrumb with better styling -->
    <nav aria-label="breadcrumb" class="mb-4 bg-light rounded-3 p-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active">
                <i class="fas fa-home"></i> Dashboard Akademik
            </li>
        </ol>
    </nav>

    <!-- Stats Cards with Icons and Better Styling -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary p-3 me-3">
                            <i class="fas fa-door-open text-white"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted">Ruang Kelas</h6>
                            <h2 class="card-title mb-0">{{$ruangKelas}}</h2>
                        </div>
                    </div>
                    <p class="card-text text-muted">Total ruang kelas aktif</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success p-3 me-3">
                            <i class="fas fa-user-graduate text-white"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted">Mahasiswa</h6>
                            <h2 class="card-title mb-0">{{$mahasiswa}}</h2>
                        </div>
                    </div>
                    <p class="card-text text-muted">Total mahasiswa aktif</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-info p-3 me-3">
                            <i class="fas fa-book text-white"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted">Mata Kuliah</h6>
                            <h2 class="card-title mb-0">{{$matakuliah}}</h2>
                        </div>
                    </div>
                    <p class="card-text text-muted">Total mata kuliah tersedia</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-warning p-3 me-3">
                            <i class="fas fa-chalkboard-teacher text-white"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted">Dosen</h6>
                            <h2 class="card-title mb-0">{{$pembimbingakd}}</h2>
                        </div>
                    </div>
                    <p class="card-text text-muted">Total dosen aktif</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .rounded-circle {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center
    }
</style>
@endpush

@endsection