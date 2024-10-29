@extends('mahasiswa.mainMhs')
@section('title', 'Akademisi Mahasiswa')

@section('content')
<!-- Page Content -->
<div class="container-fluid py-4" style="margin-top: 50px; margin-left:10px">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Akademisi Mahasiswa</li>
        </ol>
    </nav>
    <div class="row g-4">
        <!-- User Info Card -->
        <div class="col-md-8">
            <div class="card h-100" style="border:none; border-radius: 1rem; background: hsla(0, 0%, 100%, 1);

background: linear-gradient(45deg, hsla(0, 0%, 100%, 1) 0%, hsla(209, 100%, 89%, 1) 14%, hsla(217, 100%, 66%, 1) 49%);

background: -moz-linear-gradient(45deg, hsla(0, 0%, 100%, 1) 0%, hsla(209, 100%, 89%, 1) 14%, hsla(217, 100%, 66%, 1) 49%);

background: -webkit-linear-gradient(45deg, hsla(0, 0%, 100%, 1) 0%, hsla(209, 100%, 89%, 1) 14%, hsla(217, 100%, 66%, 1) 49%);">
                <div class=" row card-body">
                    <div class="col-sm-4">
                        <img src="{{ asset('img/pakvinsen.jpeg') }}" alt="avatar" class="rounded-circle img-fluid mb-3" style="width: 200px; margin:2rem">
                    </div>
                    <div class="col-sm-4" style="margin-left:4rem; margin-top: 3.5rem;">
                        <h4>{{ auth()->user()->name }}</h4>
                        <p class="text-light mb-1">{{ auth()->user()->email }}</p>
                        <p class="text-light mb-1">NIM: 242345678000</p>
                        <p class="text-light mb-1">No. Telp: (098) 765-4321</p>
                        <p class="text-light mb-0">Alamat: Pandeglang, Banten</p>
                    </div>
                </div>
            </div>
        </div>


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

    </div>
</div>
@endsection