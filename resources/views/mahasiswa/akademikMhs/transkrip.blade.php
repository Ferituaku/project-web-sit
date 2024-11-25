@extends('mahasiswa.akademikMhs.akademik-base')
@section('akademik-content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Transkrip Nilai</h5>
            </div>
            <div class="card-body">
                <!-- Summary Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Ringkasan Akademik</h6>
                                <div class="row">
                                    <div class="col-6">Total SKS:</div>
                                    <div class="col-6 text-end">100</div>
                                </div>
                                <div class="row">
                                    <div class="col-6">IPK:</div>
                                    <div class="col-6 text-end">3.85</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transkrip Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Semester</th>
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
                                <td>1</td>
                                <td>INF1234</td>
                                <td>Pemrograman Web</td>
                                <td>3</td>
                                <td>A</td>
                                <td>4.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection