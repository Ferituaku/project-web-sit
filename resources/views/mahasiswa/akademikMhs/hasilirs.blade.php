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
            <h5 class="card-title mb-0">Rekap IRS</h5>
        </div>
        <div class="card-body">
            {{-- Debug Information --}}
            <div class="mb-3">
                <p>Total Records: {{ $irsRecords->count() }}</p>
            </div>

            {{-- Semester Dropdown --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <select class="form-select" id="semester-select">
                        <option value="">Pilih Semester</option>
                        @foreach($irsRecords as $period => $records)
                        <option value="{{ $period }}">{{ $period }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- IRS Tables --}}
            @foreach($irsRecords as $period => $records)
            <div class="irs-table" id="period-{{ md5($period) }}">
                @foreach($records as $irs)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-0">Status:
                                @if($irs->approval == '0')
                                <span class="badge bg-warning">Menunggu Persetujuan</span>
                                @elseif($irs->approval == '1')
                                <span class="badge bg-success">Disetujui</span>
                                @else
                                <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </h6>
                            <small>Total SKS: {{ $irs->total_sks }}</small>
                        </div>
                        <div>
                            <a href="{{ route('mahasiswa.akademikMhs.cetak-irs', [
                                'tahunAjaran' => $irs->tahun_ajaran,
                                'semester' => $irs->semester
                            ]) }}"
                                class="btn btn-primary btn-sm">
                                <i class="bi bi-printer"></i> Cetak IRS
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode MK</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Kelas</th>
                                    <th>Jadwal</th>
                                    <th>Semester</th>
                                    <th>Dosen Pengampu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($irs->jadwalKuliah as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->kodemk }}</td>
                                    <td>{{ $jadwal->matakuliah->nama_mk }}</td>
                                    <td>{{ $jadwal->matakuliah->sks }}</td>
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
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const semesterSelect = document.getElementById('semester-select');
        const irsTables = document.querySelectorAll('.irs-table');

        // Debug logs
        console.log('Select element:', semesterSelect);
        console.log('IRS tables:', irsTables);

        // Initially hide all tables
        irsTables.forEach(table => {
            table.style.display = 'none';
        });

        semesterSelect.addEventListener('change', function() {
            const selectedPeriod = this.value;
            console.log('Selected period:', selectedPeriod);

            irsTables.forEach(table => {
                const periodId = `period-${md5(selectedPeriod)}`;
                console.log('Checking table:', table.id, 'against', periodId);

                if (table.id === periodId) {
                    table.style.display = 'block';
                    console.log('Showing table:', table.id);
                } else {
                    table.style.display = 'none';
                }
            });
        });

        // Show first option if available
        if (semesterSelect.options.length > 1) {
            semesterSelect.value = semesterSelect.options[1].value;
            semesterSelect.dispatchEvent(new Event('change'));
        }
    });

    // Simple MD5 implementation for consistent IDs
    function md5(string) {
        let hash = 0;
        for (let i = 0; i < string.length; i++) {
            const char = string.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash;
        }
        return Math.abs(hash).toString(36);
    }
</script>
@endpush