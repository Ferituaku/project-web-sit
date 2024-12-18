@extends('dosen.mainDsn')
@section('title', 'Verifikasi IRS')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dosen.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Verifikasi IRS</li>
        </ol>
    </nav>

    <!-- Alert Section -->
    <div id="alert-container"></div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-dark">Daftar IRS Mahasiswa untuk Diverifikasi</h5>
        </div>

        <div class="card-body">
            <!-- Search Section -->
            <div class="row mb-3">
                <div class="col-md-6 offset-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari mahasiswa..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
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
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Semester</th>
                            <th>Total SKS</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($irs as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration + $irs->firstItem() - 1}}</td>
                            <td>{{ $item->mahasiswa->nim }}</td>
                            <td>{{ $item->mahasiswa->name }}</td>
                            <td>{{ $item->semester }}</td>
                            <td>{{ $item->total_sks }} SKS</td>
                            <td>
                                @switch($item->approval)
                                @case(0)
                                <span class="badge bg-warning">Pending</span>
                                @break
                                @case(1)
                                <span class="badge bg-success">Disetujui</span>
                                @break
                                @case(2)
                                <span class="badge bg-danger">Ditolak</span>
                                @break
                                @endswitch
                            </td>
                            <td>
                                @if($item->approval == 0)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info me-1"
                                        onclick="showIRSDetail('{{ $item->id }}')"
                                        data-bs-toggle="modal"
                                        data-bs-target="#irsDetailModal">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                    <button class="btn btn-sm btn-success me-1" onclick="approveIRS('{{ $item->id }}')">
                                        <i class="bi bi-check-circle me-1"></i>Setujui
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectIRS('{{ $item->id }}')">
                                        <i class="bi bi-x-circle me-1"></i>Tolak
                                    </button>
                                </div>
                                @elseif($item->approval == 1)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info me-1"
                                        onclick="showIRSDetail('{{ $item->id }}')"
                                        data-bs-toggle="modal"
                                        data-bs-target="#irsDetailModal">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                    <button class="btn btn-primary btn-sm me-1" onclick="printIrsMhs('{{ $item->id }}')">
                                        <i class="bi bi-printer me-1"></i>Cetak IRS
                                    </button>
                                    <!-- Add Edit button -->
                                    <button class="btn btn-sm btn-primary me-1"
                                        onclick="enableIRSEdit('{{ $item->id }}', '{{ $item->updated_at }}')"
                                        title="Buka edit IRS">
                                        <i class="bi bi-pencil me-1"></i>Edit Akses
                                    </button>
                                    <!-- Existing cancel button -->
                                    <button class="btn btn-sm btn-warning"
                                        onclick="cancelApprovedIRS('{{ $item->id }}', '{{ $item->updated_at }}')"
                                        title="Batalkan IRS yang sudah disetujui">
                                        <i class="bi bi-x-circle me-1"></i>Batalkan
                                    </button>
                                </div>
                                @else
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-danger" onclick="cancelIrs('{{ $item->id }}')">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data IRS</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($irs->total() > 0)
            <div class="card-footer d-flex justify-content-between align-items-center py-3">
                <div class="text-sm text-muted">
                    Menampilkan {{ $irs->firstItem() }} - {{ $irs->lastItem() }}
                    dari {{ $irs->total() }} IRS
                </div>
                <div>
                    {{ $irs->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- IRS Detail Modal -->
<div class="modal fade" id="irsDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail IRS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="irsDetailContent">
                    <div class="alert alert-info mb-3" id="cancellation-period-info" style="display: none;">
                        <i class="bi bi-info-circle me-2"></i>
                        <span>Periode pembatalan IRS: <span id="remaining-days"></span> hari lagi</span>
                    </div>
                    <div class="mb-3">
                        <h6>Total SKS: <span id="total-sks">0</span>/24</h6>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%;"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="24">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode MK</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Kelas</th>
                                    <th>Jadwal</th>
                                    <th>Dosen Pengampu</th>
                                </tr>
                            </thead>
                            <tbody id="selected-courses-table">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scriptDsn')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = '{{ csrf_token() }}';

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

        window.approveIRS = function(id) {
            if (confirm('Apakah Anda yakin ingin setujui IRS ini?')) {
                fetch(`/dosen/irs/${id}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        showAlert(data.status === 'success' ? 'success' : 'error', data.message);
                        if (data.status === 'success') location.reload();
                    }).catch(() => {
                        showAlert('error', 'Terjadi kesalahan sistem');
                    });
            }
        };

        window.rejectIRS = function(id) {
            if (confirm('Apakah Anda yakin ingin menolak IRS ini?')) {
                fetch(`/dosen/irs/${id}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        showAlert(data.status === 'success' ? 'success' : 'error', data.message);
                        if (data.status === 'success') location.reload();
                    }).catch(() => {
                        showAlert('error', 'Terjadi kesalahan sistem');
                    });
            }
        };
        window.cancelApprovedIRS = function(id, approvalDate) {
            // Cek periode pembatalan
            const fourWeeks = 28 * 24 * 60 * 60 * 1000; // 4 minggu dalam milidetik
            const approvalTime = new Date(approvalDate).getTime();
            const now = new Date().getTime();

            if ((now - approvalTime) > fourWeeks) {
                showAlert('error', 'Periode pembatalan IRS telah berakhir (4 minggu setelah persetujuan)');
                return;
            }

            if (confirm('Apakah Anda yakin ingin membatalkan IRS yang sudah disetujui ini? Mahasiswa harus mengisi ulang IRS setelah pembatalan.')) {
                fetch(`/dosen/irs/${id}/cancel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showAlert(data.status === 'success' ? 'success' : 'error', data.message);
                        if (data.status === 'success') {
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    })
                    .catch(() => {
                        showAlert('error', 'Terjadi kesalahan sistem');
                    });
            }
        };

        window.printIrsMhs = function(id) {
            window.open(`/dosen/irs/${id}/print`, '_blank');
        };
    });

    function updateCancellationPeriodInfo(approvalDate) {
        const fourWeeks = 28 * 24 * 60 * 60 * 1000; // 4 minggu dalam milidetik
        const approvalTime = new Date(approvalDate).getTime();
        const now = new Date().getTime();
        const remainingTime = fourWeeks - (now - approvalTime);

        const periodInfo = document.getElementById('cancellation-period-info');
        const remainingDays = document.getElementById('remaining-days');

        if (remainingTime > 0) {
            const days = Math.ceil(remainingTime / (24 * 60 * 60 * 1000));
            remainingDays.textContent = days;
            periodInfo.style.display = 'block';
        } else {
            periodInfo.style.display = 'none';
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        window.enableIRSEdit = function(id, approvalDate) {
            // Check edit period
            const twoWeeks = 14 * 24 * 60 * 60 * 1000; // 2 minggu dalam milidetik
            const approvalTime = new Date(approvalDate).getTime();
            const now = new Date().getTime();

            if ((now - approvalTime) > twoWeeks) {
                showAlert('error', 'Periode edit IRS telah berakhir (2 minggu setelah persetujuan)');
                return;
            }

            if (confirm('Apakah Anda yakin ingin membuka edit IRS ini? Status IRS akan kembali menjadi pending.')) {
                fetch(`/dosen/irs/${id}/enable-edit`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showAlert(data.status === 'success' ? 'success' : 'error', data.message);
                        if (data.status === 'success') {
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    })
                    .catch(() => {
                        showAlert('error', 'Terjadi kesalahan sistem');
                    });
            }
        };
        window.showIRSDetail = function(id) {
            const modalBody = document.getElementById('irsDetailContent');
            const totalSksElement = document.getElementById('total-sks');
            const coursesTableBody = document.getElementById('selected-courses-table');
            const progressBar = document.querySelector('.progress-bar');

            modalBody.querySelector('.table-responsive').style.display = 'none';
            modalBody.insertAdjacentHTML('afterbegin', '<div class="text-center py-3">Memuat data...</div>');

            fetch(`/dosen/irs/${id}/detail`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    modalBody.querySelector('.text-center').remove();
                    modalBody.querySelector('.table-responsive').style.display = 'block';

                    if (data.status === 'success') {
                        const irs = data.irs;
                        if (irs.approval === '1') {
                            updateCancellationPeriodInfo(irs.updated_at);
                        }
                        // Update total SKS and progress bar
                        totalSksElement.textContent = irs.total_sks;
                        const progressPercentage = Math.min((irs.total_sks / 24) * 100, 100);
                        progressBar.style.width = `${progressPercentage}%`;
                        progressBar.setAttribute('aria-valuenow', irs.total_sks);

                        // Update courses table
                        coursesTableBody.innerHTML = '';
                        irs.courses.forEach(course => {
                            coursesTableBody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td>${course.kodemk}</td>
                            <td>${course.nama_mk}</td>
                            <td>${course.sks}</td>
                            <td>${course.kelas}</td>
                            <td>${course.schedule || '-'}</td>
                            <td>${course.dosen}</td>
                        </tr>
                    `);
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    modalBody.innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat data: ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
                });
        };
    });
</script>
@endsection