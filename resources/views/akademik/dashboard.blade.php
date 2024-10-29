@extends('akademik.mainAkd')
@section('title', 'Dashboard Akademik')

@section('content')


<!-- Main Content -->
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard Akademik</li>
        </ol>
    </nav>


    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Ruang Kelas</h5>
                    <p class="card-text">20</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Mahasiswa</h5>
                    <p class="card-text">1000</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Mata Kuliah</h5>
                    <p class="card-text">10</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Pendaftaran</h5>
                    <p class="card-text">40</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <a href="#" class="btn btn-primary">Manajemen Ruang Kelas</a>
        </div>
        <div class="col-md-4">
            <a href="#" class="btn btn-primary">Manajemen Mahasiswa</a>
        </div>
        <div class="col-md-4">
            <a href="#" class="btn btn-primary">Manajemen Mata Kuliah</a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <a href="#" class="btn btn-primary">Manajemen Pendaftaran</a>
        </div>
    </div>
</div>



@endsection