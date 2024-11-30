@extends('mahasiswa.mainMhs')
@section('title', 'Akademik')
@section('content')

<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Akademik</li>
        </ol>
    </nav>

    {{-- Alert for messages --}}
    @if(session('success') || session('error'))
    <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
        {{ session('success') ?? session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-10 mb-5">
            <a href="{{ route('mahasiswa.akademikMhs.buatIrs') }}" class="btn btn-primary {{ Request::routeIs('mahasiswa.akademik.irs') ? 'active' : '' }}">Buat IRS</a>
            <a href="{{ route('mahasiswa.akademikMhs.hasilirs') }}" class="btn btn-primary {{ Request::routeIs('mahasiswa.akademik.hasilirs') ? 'active' : '' }}">IRS</a>
            <a href="{{ route('mahasiswa.akademikMhs.khs') }}" class="btn btn-primary {{ Request::routeIs('mahasiswa.akademik.khs') ? 'active' : '' }}">KHS</a>
            <a href="{{ route('mahasiswa.akademikMhs.transkrip') }}" class="btn btn-primary {{ Request::routeIs('mahasiswa.akademik.transkrip') ? 'active' : '' }}">Transkrip</a>
        </div>

        {{-- Content Section --}}
        <div class="col-12">
            @yield('akademik-content')
        </div>
    </div>
</div>

@endsection

@section('style')
@yield('page-style')
@endsection

@section('scripts')
@yield('page-scripts')
@endsection