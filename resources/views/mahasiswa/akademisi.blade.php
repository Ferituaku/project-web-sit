@extends('mahasiswa.mainMhs')
@section('title', 'Buat IRS')

@section('content')
<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('mahasiswa.akademisi') }}">Akademisi</a></li>
            <li class="breadcrumb-item active">Buat IRS</li>
        </ol>
    </nav>

    <div class="row">
        <!-- SKS Counter Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="col align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Total SKS Dipilih: <span id="selected-sks">0</span>/24</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary" id="submit-irs" disabled>
                            Simpan IRS
                        </button>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Filter Semester</label>
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
            </div>
        </div>



        <!-- Schedule Matrix Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Buat IRS</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 100px;">Jam</th>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                                <th class="text-center">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<style>
    .schedule-card {
        cursor: pointer;
        transition: all 0.2s ease;
        overflow: hidden;
    }

    .schedule-card:hover {
        transform: scale(1.02);
        z-index: 2;
    }

    .schedule-card.selected-class {
        background-color: #e3f2fd !important;
        border: 2px solid #2196f3 !important;
    }
</style>
@endsection