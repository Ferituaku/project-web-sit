@extends('dekan.mainDkn')
@section('title', 'Persetujuan Jadwal')

@section('content')

<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dekan.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Persetujuan Jadwal</li>
        </ol>
    </nav>
    <!-- Alert Section -->
    <div id="alert-container"></div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-dark">Daftar Jadwal Kuliah </h5>
        </div>

        <div class="card-body">
            <!-- Search Section -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select" id="semester-filter">
                        <option value="">Pilih Program Studi .. </option>

                        <option>Informatika</option>

                    </select>
                </div>
                <div class="col-md-3 offset-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari ruang kelas..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode MK</th>
                            <th>Mata Kuliah</th>
                            <th>Dosen</th>
                            <th>SKS</th>
                            <th>Semester
                                <button id="sortSemester" class="btn btn-sm p-0" title="Urutkan Semester">
                                    <i id="sortIcon" class="bi bi-sort-down"></i>
                                </button>
                            </th>
                            <th>Kelas</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Ruangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalKuliah as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $jadwal->matakuliah->kodemk }}</td>
                            <td>{{ $jadwal->matakuliah->nama_mk }}</td>
                            <td>{{ $jadwal->pembimbingakd->name }}</td>
                            <td>{{ $jadwal->matakuliah->sks }}</td>
                            <td>{{ $jadwal->plot_semester }}</td>
                            <td>{{ $jadwal->class_group }}</td>
                            <td>{{ $jadwal->hari }}</td>
                            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td>{{ $jadwal->ruangKelas->koderuang }}</td>
                            <td>
                                @if($jadwal->approval == '0')
                                <span class="badge bg-warning">Pending</span>
                                @elseif($jadwal->approval == '1')
                                <span class="badge bg-success">Disetujui</span>
                                @else
                                <span class="badge bg-danger" data-bs-toggle="tooltip" title="{{ $jadwal->rejection_reason }}">
                                    Ditolak
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($jadwal->approval == '0')
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-success me-1" onclick="approveJadwal({{$jadwal->id}})">
                                        <i class="bi bi-check-circle me-1"></i>Setujui
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="showRejectModalJadwal({{$jadwal->id}})">
                                        <i class="bi bi-x-circle me-1"></i>Tolak
                                    </button>
                                </div>
                                @elseif($jadwal->approval == '1')
                                <span class="text-muted">Sudah disetujui</span>
                                @elseif($jadwal->approval == '2')
                                <span class="text-muted">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    {{ $jadwalKuliah->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <input type="hidden" id="jadwal_id" name="jadwal_id">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="submitRejection()">Kirim</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function approveJadwal(id) {
        if (!confirm('Apakah Anda yakin ingin menyetujui jadwal ini?')) return;

        fetch(`/dekan/jadwal/${id}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => showAlert('error', 'Terjadi kesalahan saat memproses permintaan'));
    }

    function submitRejection() {
        const jadwalId = document.getElementById('jadwal_id').value;
        const reason = document.getElementById('rejection_reason').value;

        if (!reason.trim()) {
            alert('Harap isi alasan penolakan');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/dekan/jadwal/${jadwalId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    rejection_reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
                    modal.hide();
                    showAlert('success', data.message);
                    if (data.reload) {
                        setTimeout(() => location.reload(), 1500);
                    }
                } else {
                    showAlert('error', data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat memproses permintaan');
            });
    }

    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

        alertContainer.innerHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    }
    document.addEventListener('DOMContentLoaded', function() {

        let isAscending = true;

        // Fungsi untuk mengurutkan tabel berdasarkan kolom semester
        function sortTableBySemester() {
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                const semesterA = parseInt(a.querySelector('td:nth-child(5)').textContent);
                const semesterB = parseInt(b.querySelector('td:nth-child(5)').textContent);
                return isAscending ? semesterA - semesterB : semesterB - semesterA;
            });

            // Bersihkan isi tabel dan tambahkan baris yang sudah diurutkan
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));

            // Perbarui ikon panah berdasarkan urutan
            document.getElementById('sortIcon').className = isAscending ? 'bi btn-outline-light  bi-sort-down' : 'bi btn-outline-light bi-sort-up';

            // Ubah arah urutan untuk klik berikutnya
            isAscending = !isAscending;
        }

        // Event listener untuk tombol sort
        document.getElementById('sortSemester').addEventListener('click', sortTableBySemester);

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush