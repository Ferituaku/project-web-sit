@extends('akademik.mainAkd')
@section('title', 'Dashboard Akademik')

@section('content')


<!-- Main Content -->
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard Akademik</li>
        </ol>
    </nav>


    <div class="card shadow-sm">
        <div class="col-md-2 mt-3">
            <div class="card mb-4 mb-md-0" style="border-radius: 1rem; backdrop-filter: blur(10px); background: rgba(91, 84, 184, 0.4);">
                <div class="card-body">
                    <div class=" text-center">

                        <h2 class="mb-0">Status Akademik</h2>

                        <div class="d-flex justify-content-between align-items-center mt-4 px-5">
                            <div class="stats">
                                <h4 class="mb-0 ">Semester Akademik Sekarang</h4>
                                <span>2024/2025 Ganjil</span>
                            </div>
                            <div class="stats">
                                <h4 class="mb-0 px-2">Semester</h4>
                                <span>5</span>

                            </div>


                            <div class="stats">
                                <h4 class="mb-0 px-4">Status</h4>
                                <span class="btn active btn-success">AKTIF</span>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>





@endsection