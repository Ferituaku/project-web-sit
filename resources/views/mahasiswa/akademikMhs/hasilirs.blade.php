@extends('mahasiswa.akademikMhs.akademik-base')
@section('akademik-content')

<div class="container-fluid py-4">
    {{-- Alert Messages --}}
    @if(session('success') || session('error'))
    <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
        {{ session('success') ?? session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif


    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Rekap IRS</h5>
                <div class="text-muted">
                    <small>Semester Aktif: {{ $mahasiswa->semester }} ({{ $mahasiswa->tahun_ajaran }})</small>
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- Semester Dropdown --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-calendar-range"></i>
                        </span>
                        <select class="form-select" id="semester-select">
                            <option value="">Pilih Periode</option>
                            @foreach($irsRecords as $period => $records)
                            <option value="{{ md5($period) }}">{{ $period }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- IRS Tables --}}
            @foreach($irsRecords as $period => $records)
            <div class="irs-table" id="period-{{ md5($period) }}">
                @foreach($records as $irs)
                <div class="mb-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Status IRS:
                                        @if($irs->approval == '0')
                                        <span class="badge bg-warning">Menunggu Persetujuan</span>
                                        @elseif($irs->approval == '1')
                                        <span class="badge bg-success">Disetujui</span>
                                        @else
                                        <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </h6>
                                    <div class="small">
                                        <span class="text-muted">Total SKS Semester Ini:</span>
                                        <span class="fw-bold">{{ $irs->total_sks }}</span>
                                    </div>
                                </div>
                                @if($irs->approval == '1')
                                <div>
                                    <a href="{{ route('mahasiswa.akademikMhs.cetak-irs', [
                                        'tahunAjaran' => $irs->tahun_ajaran,
                                        'semester' => $irs->semester
                                    ]) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-printer"></i> Cetak IRS
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode MK</th>
                                        <th>Mata Kuliah</th>
                                        <th class="text-center">SKS</th>
                                        <th>Kelas</th>
                                        <th>Jadwal</th>
                                        <th>Semester</th>
                                        <th>Dosen Pengampu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($irs->jadwalKuliah as $jadwal)
                                    <tr>
                                        <td><code>{{ $jadwal->kodemk }}</code></td>
                                        <td>{{ $jadwal->matakuliah->nama_mk }}</td>
                                        <td class="text-center">{{ $jadwal->matakuliah->sks }}</td>
                                        <td>{{ $jadwal->class_group }}</td>
                                        <td>{{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                        <td>{{ $jadwal->plot_semester }}</td>
                                        <td>{{ $jadwal->pembimbingakd->name ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            @if($irsRecords->isEmpty())
            <div class="alert alert-info">
                Belum ada data IRS yang tersedia.
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const semesterSelect = document.getElementById('semester-select');
        const irsTables = document.querySelectorAll('.irs-table');

        // Initially hide all tables
        irsTables.forEach(table => table.style.display = 'none');

        semesterSelect.addEventListener('change', function() {
            const selectedPeriod = this.value;

            irsTables.forEach(table => {
                table.style.display = table.id === `period-${selectedPeriod}` ? 'block' : 'none';
            });
        });

        // Show first semester if available
        if (semesterSelect.options.length > 1) {
            semesterSelect.value = semesterSelect.options[1].value;
            semesterSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush