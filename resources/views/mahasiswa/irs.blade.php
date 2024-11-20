@extends('mahasiswa.mainMhs')
@section('title', 'IRS')

@section('content')
<!-- Page Content -->
<div class="container-fluid py-4" style="margin-top: 50px; margin-left:10px">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">irs</li>
        </ol>
    </nav>
    <div class="row g-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <th>Nama Mata Kuliah</th>
                        <th>Kode MK</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Dosen Pengampu</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <a href="#" class="btn btn-primary">Cetak KHS</a>
        </div>
    </div>
</div>
@endsection