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
            <div class="col mb-2">
                Status : <span class="">Menunggu Persetujuan</span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <th>Kode MK</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Kelas</th>
                        <th>Jadwal</th>
                        <th>Semester</th>
                        <th>Dosen Pengampu</th>
                    </thead>
                    <tbody>
                        <!-- Your IRS data will be inserted here -->
                        
                    </tbody>
                </table>
            </div>
            <a href="#" class="btn btn-primary">Cetak IRS</a>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script>

</script>