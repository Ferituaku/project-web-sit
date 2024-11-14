@extends('mahasiswa.mainMhs')
@section('title', 'HerReg Mahasiswa')

@section('content')
<!-- Page Content -->
<div class="container-fluid py-4" style="margin-top: 50px; margin-left:10px">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Her-Registrasi</li>
        </ol>
    </nav>
    <div class="row g-4">

        <div class="row g-4">

            <!-- Academic Status Card -->
            <div class="col-md-8">
                <div class="card" style="border:none;  border-radius: 1rem; background: hsla(0, 0%, 100%, 1);

background: linear-gradient(45deg, hsla(0, 0%, 100%, 1) 0%, hsla(209, 100%, 89%, 1) 14%, hsla(217, 100%, 66%, 1) 49%);

background: -moz-linear-gradient(45deg, hsla(0, 0%, 100%, 1) 0%, hsla(209, 100%, 89%, 1) 14%, hsla(217, 100%, 66%, 1) 49%);

background: -webkit-linear-gradient(45deg, hsla(0, 0%, 100%, 1) 0%, hsla(209, 100%, 89%, 1) 14%, hsla(217, 100%, 66%, 1) 49%);


">
                    <div class="card-body text-center mb-2">
                        <h2 class="mb-4">Status Akademik</h2>
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Semester Akademik Sekarang</h5>
                                <p>2024/2025 Ganjil</p>
                            </div>
                            <div class="col-md-4">
                                <h5>Semester</h5>
                                <p>5</p>
                            </div>
                            <div class="col-md-4">
                                <h5>Status</h5>
                                <span class="badge bg-success">AKTIF</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GPA and Credits Card -->
            <div class="col-md-4">
                <div class="card" style="border:none; border-radius: 1rem; background: hsla(0, 0%, 100%, 1);

background: linear-gradient(45deg, hsla(0, 0%, 100%, 1) 0%, hsla(209, 100%, 89%, 1) 14%, hsla(217, 100%, 66%, 1) 49%);

background: -moz-linear-gradient(45deg, hsla(0, 0%, 100%, 1) 0%, hsla(209, 100%, 89%, 1) 14%, hsla(217, 100%, 66%, 1) 49%);

background: -webkit-linear-gradient(45deg, hsla(0, 0%, 100%, 1) 0%, hsla(209, 100%, 89%, 1) 14%, hsla(217, 100%, 66%, 1) 49%);


">
                    <div class="card-body text-center">
                        <h4 class="mb-1">IPK</h4>
                        <p class="h2 mb-2">3.9</p>
                        <h4 class="mb-1">SKS</h4>
                        <p class="h2">86</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endsection