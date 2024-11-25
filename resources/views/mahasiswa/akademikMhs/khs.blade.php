@extends('mahasiswa.akademikMhs.akademik-base')
@section('akademik-content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Kartu Hasil Studi</h5>
            </div>
            <div class="card-body">
                <!-- Semester Selection -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <select class="form-select" id="semester-select">
                            <option value="">Pilih Semester</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">Semester {{ $i }}</option>
                                @endfor
                        </select>
                    </div>
                </div>

                <!-- KHS Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode MK</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Nilai</th>
                                <th>Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample data - replace with actual data -->
                            <tr>
                                <td>INF1234</td>
                                <td>Pemrograman Web</td>
                                <td>3</td>
                                <td>A</td>
                                <td>4.00</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total SKS:</td>
                                <td class="fw-bold">21</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">IP Semester:</td>
                                <td colspan="3" class="fw-bold">3.85</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection