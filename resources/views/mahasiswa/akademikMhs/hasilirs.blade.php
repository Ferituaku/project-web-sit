@extends('mahasiswa.akademikMhs.akademik-base')
@section('akademik-content')

<!-- Page Content -->

<div class="container-fluid py-4">

    {{-- Alert for messages --}}
    @if(session('success') || session('error'))
    <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
        {{ session('success') ?? session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

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
                        <td>
                            <!-- Your IRS data will be inserted here -->

                        </td>
                    </tbody>
                </table>
            </div>
            <a href="#" class="btn btn-primary">Cetak IRS</a>
        </div>
    </div>
</div>
@endsection