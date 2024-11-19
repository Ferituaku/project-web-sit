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
    <div class="row ">
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary w-100 mb-3" id="">
                Buat IRS
            </button>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary w-100 mb-3" id="">
                KHS
            </button>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary w-100 mb-3" id="">
                Transkrip
            </button>
        </div>

    </div>
    <div class="row ">
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
                                                <small class="d-block">Kelas: {{ $jadwal->class_group }}</small>
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

</div>
<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i id="alert-icon" class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 id="alert-message" class="mb-0">Mata kuliah berhasil ditambahkan!</h4>
            </div>
        </div>
    </div>
</div>


<!-- Alert Modal
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
</div> -->
@endsection

@section('style')
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const courseSelect = document.getElementById('course-select');
        const selectedCoursesList = document.getElementById('selected-courses-list');
        const submitIrsButton = document.getElementById('submit-irs');
        const totalSksElement = document.getElementById('total-sks');
        const progressBar = document.querySelector('.progress-bar');
        const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
        const alertMessage = document.getElementById('alert-message');

        // State
        let selectedCourses = [];
        const MAX_SKS = 24;
        let currentTotalSks = 0;

        // Schedule cells
        const scheduleCells = document.querySelectorAll('.schedule-cell');

        // Event Listeners
        courseSelect.addEventListener('change', handleCourseSelection);
        submitIrsButton.addEventListener('click', handleIrsSave);

        // Schedule cell click handler
        scheduleCells.forEach(cell => {
            cell.addEventListener('click', handleScheduleCellClick);
        });

        function handleCourseSelection(e) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            if (!selectedOption.value) return;

            const courseDetails = {
                kode: selectedOption.value,
                nama: selectedOption.dataset.nama,
                sks: parseInt(selectedOption.dataset.sks),
                semester: selectedOption.dataset.semester,
                class_group: selectedOption.dataset.class_group
            };

            // Check if course already selected
            if (selectedCourses.some(course => course.kode === courseDetails.kode)) {
                showAlert('Mata kuliah sudah dipilih sebelumnya.');
                return;
            }

            // Check SKS limit
            if (currentTotalSks + courseDetails.sks > MAX_SKS) {
                showAlert('Total SKS melebihi batas maksimum 24 SKS.');
                return;
            }

            // Add course to selected list
            selectedCourses.push(courseDetails);
            updateSelectedCoursesList();
            updateSksCounter(courseDetails.sks);
        }

        function handleScheduleCellClick(e) {
            const scheduleItem = e.target.closest('.schedule-item');
            if (!scheduleItem) return;

            const courseDetails = {
                kode: scheduleItem.querySelector('small:nth-child(2)').textContent.replace('Kode: ', ''),
                nama: scheduleItem.querySelector('small:first-child').textContent,
                sks: parseInt(scheduleItem.dataset.sks),
                class_group: scheduleItem.querySelector('small:nth-child(3)').textContent.replace('Kelas: ', '')

            };

            // Check if course already selected
            if (selectedCourses.some(course => course.kode === courseDetails.kode)) {
                showAlert('Mata kuliah sudah dipilih sebelumnya.');
                return;
            }

            // Check SKS limit
            if (currentTotalSks + courseDetails.sks > MAX_SKS) {
                showAlert('Total SKS melebihi batas maksimum 24 SKS.');
                return;
            }

            // Add course
            selectedCourses.push(courseDetails);
            updateSelectedCoursesList();
            updateSksCounter(courseDetails.sks);

            // Show success modal
            showAlert('Mata kuliah berhasil ditambahkan!', 'success');
        }

        function updateSelectedCoursesList() {
            selectedCoursesList.innerHTML = selectedCourses.map((course, index) => `
            <div class="card mb-2">
                <div class="card-body d-flex justify-content-between align-items-center p-2">
                    <div>
                        <strong>${course.kode}</strong> - ${course.nama} - Kelas ${course.class_group} (${course.sks} SKS)
                    </div>
                    <button class="btn btn-sm btn-danger remove-course" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');

            // Add remove course listeners
            document.querySelectorAll('.remove-course').forEach(button => {
                button.addEventListener('click', handleRemoveCourse);
            });

            // Enable/disable submit button
            submitIrsButton.disabled = selectedCourses.length === 0;
        }

        function handleRemoveCourse(e) {
            const index = e.currentTarget.dataset.index;
            const removedCourse = selectedCourses[index];

            // Remove course and update UI
            selectedCourses.splice(index, 1);
            updateSelectedCoursesList();
            updateSksCounter(-removedCourse.sks);
        }

        function updateSksCounter(sksChange) {
            currentTotalSks += sksChange;

            // Update total SKS display
            totalSksElement.textContent = currentTotalSks;

            // Update progress bar
            const progressPercentage = (currentTotalSks / MAX_SKS) * 100;
            progressBar.style.width = `${progressPercentage}%`;
            progressBar.setAttribute('aria-valuenow', currentTotalSks);

            // Color code progress bar
            progressBar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
            if (progressPercentage < 50) {
                progressBar.classList.add('bg-warning');
            } else if (progressPercentage >= 50 && progressPercentage < 100) {
                progressBar.classList.add('bg-success');
            } else {
                progressBar.classList.add('bg-danger');
            }
        }

        function handleIrsSave(e) {
            e.preventDefault();

            if (selectedCourses.length === 0) {
                showAlert('Pilih minimal satu mata kuliah.');
                return;
            }

            // Prepare data for submission
            const irsData = {
                courses: selectedCourses.map(course => course.kode),
                total_sks: currentTotalSks
            };

            // Here you would typically make an AJAX call to save the IRS
            // For now, we'll just show a success modal
            showAlert('IRS berhasil disimpan!', 'success');
        }

        function showAlert(message, type = 'warning') {
            alertMessage.textContent = message;
            alertModal.show();

            // Optional: Add color coding or icon based on type
            const modalDialog = document.querySelector('#alertModal .modal-content');
            modalDialog.classList.remove('border-warning', 'border-success');

            if (type === 'success') {
                modalDialog.classList.add('border-success');
            } else {
                modalDialog.classList.add('border-warning');
            }
        }
    });
</script>

@endsection