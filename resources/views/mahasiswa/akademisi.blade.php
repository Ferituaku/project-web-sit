@extends('mahasiswa.mainMhs')
@section('title', 'Buat IRS')
@section('content')

<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kaprodi.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Jadwal</li>
        </ol>
    </nav>
    <div class="row shadow-sm">
        <!-- Left Panel - Course Selection -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <!-- SKS Counter -->
                    <div class="mb-3">
                        <h5>Total SKS: <span id="total-sks">0</span>/24</h5>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%;"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="24">
                            </div>
                        </div>
                    </div>

                    <!-- Course Search -->
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="search-course" class="form-control"
                                placeholder="Cari mata kuliah...">
                        </div>
                    </div>

                    <!-- Course Selection Dropdown -->
                    <form id="irs-form" action="#" method="POST">
                        @csrf
                        <select class="form-select mb-3" id="course-select">
                            <option value="">Pilih Mata Kuliah</option>
                            @foreach($matakuliah as $mk)
                            <option value="{{ $mk->kodemk }}"
                                data-sks="{{ $mk->sks }}"
                                data-nama="{{ $mk->nama_mk }}"
                                data-semester="{{ $mk->semester }}">
                                {{ $mk->kodemk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS )( SMT {{ $mk->semester}})
                            </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary w-100 mb-3" id="submit-irs" disabled>
                            Simpan IRS
                        </button>
                    </form>

                    <!-- Selected Courses List -->
                    <div class="selected-courses">
                        <h6 class="mb-3">Mata Kuliah Dipilih:</h6>
                        <div id="selected-courses-list"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Schedule -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Jadwal Kuliah</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Jam</th>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                    <th class="text-center">{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timeSlots as $time)
                                <tr>
                                    <td class="text-center">{{ $time }}</td>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                    <td class="schedule-cell p-1" data-day="{{ $day }}" data-time="{{ $time }}">
                                        @foreach($scheduleMatrix[$time][$day] as $jadwal)
                                        <div class="schedule-item card border-info mb-1"
                                            data-jadwal-id="{{ $jadwal->id }}"
                                            data-sks="{{ $jadwal->matakuliah->sks }}">
                                            <div class="card-body p-2">
                                                <small class="d-block fw-bold">{{ $jadwal->matakuliah->nama_mk }}</small>
                                                <small class="d-block">Kode: {{ $jadwal->kodemk }}</small>
                                                <small class="d-block">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Peringatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="alert-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .schedule-card {
        transition: transform 0.2s ease;
    }

    .schedule-card:hover {
        transform: scale(1.02);
        z-index: 1;
    }
</style>
@endsection