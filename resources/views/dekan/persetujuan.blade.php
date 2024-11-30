@extends('dekan.mainDkn')
@section('title', 'Persetujuan Ruang Kelas')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dekan.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Persetujuan Ruang Kelas</li>
        </ol>
    </nav>

    <!-- Alert Section -->
    <div id="alert-container"></div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header text-dark py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Ruang Kelas untuk Ditetapkan</h5>
                <div class="d-flex align-items-center">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Cari ruang kelas..." id="searchInput">
                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Ruang</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Program Studi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ruangKelas as $index => $ruang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ruang->koderuang }}</td>
                        <td>{{ $ruang->kapasitas }} orang</td>
                        <td>
                            @if(!$ruang->program_studi_id)
                            <form action="{{ route('dekan.ruangkelas.approve', $ruang->koderuang) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <select name="program_studi_id" class="form-select" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach($programStudi as $ps)
                                    <option value="{{ $ps->id }}"
                                        {{ $ruang->program_studi_id == $ps->id ? 'selected' : '' }}>
                                        {{ $ps->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Setujui</button>
                            </form>
                            @else
                            <span class="badge bg-success">Valid</span>
                            @endif
                        </td>
                        <td>
                            @if($ruang->program_studi_id)
                            {{-- Cari nama program studi dari koleksi programStudi --}}
                            @php
                            $programStudiNama = $programStudi->firstWhere('id', $ruang->program_studi_id)->nama ?? 'Tidak Ditemukan';
                            @endphp
                            {{ $programStudiNama }}
                            @else
                            <span class="badge bg-warning">Belum Disetujui</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($ruangKelas->total() > 0)
        <div class="card-footer d-flex justify-content-between align-items-center py-3">
            <div class="text-sm text-muted">
                Menampilkan {{ $ruangKelas->firstItem() }} - {{ $ruangKelas->lastItem() }}
                dari {{ $ruangKelas->total() }} ruang kelas
            </div>
            <div>
                {{ $ruangKelas->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableBody = document.querySelector('tbody');
        const rows = tableBody.getElementsByTagName('tr');

        for (let row of rows) {
            const koderuang = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            row.style.display = koderuang.includes(searchValue) ? '' : 'none';
        }
    });

    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        alertContainer.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

    function approveRoom(koderuang) {
        fetch(`/dekan/ruangkelas/${koderuang}/approve`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    location.reload();
                } else {
                    showAlert('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                showAlert('error', 'Terjadi kesalahan sistem.');
            });
    }

    function rejectRoom(koderuang) {
        fetch(`/dekan/ruangkelas/${koderuang}/reject`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    location.reload();
                } else {
                    showAlert('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                showAlert('error', 'Terjadi kesalahan sistem.');
            });
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableBody = document.querySelector('tbody');
        const rows = tableBody.getElementsByTagName('tr');

        for (let row of rows) {
            const koderuang = row.cells[1].textContent.toLowerCase();
            row.style.display = koderuang.includes(searchValue) ? '' : 'none';
        }
    });
</script>
@endsection