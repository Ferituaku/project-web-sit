@extends('kaprodi.mainKpd')
@section('title', 'Daftar Jadwal')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kaprodi.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Daftar Jadwal</li>
        </ol>
    </nav>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 text-dark">Daftar Jadwal Perkuliahan</h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <button class="btn btn-secondary ms-2" id="refreshTable">
                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Search and Filter Section -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select" id="semester-filter">
                        <option value="">Semua Semester</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>
                            Semester {{ $i }}
                            </option>
                            @endfor
                    </select>
                </div>
            </div>

            <!-- Schedule Matrix -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 100px;">Jam</th>
                            <th class="text-center">Senin</th>
                            <th class="text-center">Selasa</th>
                            <th class="text-center">Rabu</th>
                            <th class="text-center">Kamis</th>
                            <th class="text-center">Jumat</th>
                            <th class="text-center">Sabtu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeSlots as $time)
                        <tr>
                            <td class="text-center align-middle">{{ $time }}</td>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                            <td class="p-2">
                                @foreach($scheduleMatrix[$time][$day] as $jadwal)
                                @if($jadwal->approval == '1')
                                <div class="card border border-info mb-2 shadow-sm">
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1">
                                            {{ $jadwal->matakuliah->nama_mk }}
                                        </h6>
                                        <p class="card-text small mb-1">
                                            (KODE:{{$jadwal->kodemk}})(SMT: {{ $jadwal->plot_semester }})<br>
                                            SKS: {{ $jadwal->matakuliah->sks }}<br>
                                            Kelas: {{ $jadwal->class_group }} ({{$jadwal->ruangKelas->koderuang}})<br>
                                            {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                            @if($jadwal->approval == '0')
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif($jadwal->approval == '1')
                                            <span class="badge bg-success">Disetujui</span>
                                            @else
                                            <button class="badge bg-danger border-0 d-inline-flex align-items-center"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectionReasonModal"
                                                onclick="showRejectionReason(this)"
                                                data-reason="{{ $jadwal->rejection_reason }}">
                                                Ditolak
                                                <i class=" bi bi-info-circle ms-1"></i>
                                            </button>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif
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
@endsection

@section('scriptKpd')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Semester filter handling
        const semesterFilter = document.getElementById('semester-filter');
        semesterFilter.addEventListener('change', function() {
            window.location.href = `{{ route('kaprodi.daftarJadwal') }}?semester=${this.value}`;
        });

        // Refresh button handling
        document.getElementById('refreshTable').addEventListener('click', function() {
            window.location.reload();
        });
    });
</script>
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