@extends('akademik.mainAkd')
@section('title', 'Dashboard Akademik')

@section('content')


<!-- Main Content -->
<main class="flex-grow-1" style="margin-left: 250px;">

    <div class="container-lg py-1">
        <div class="row" style="margin-left:25vh; margin-top:10vh">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard Akademik</li>
                </ol>
            </nav>

            <div class="col-md-9 mt-3">
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
</main>
</div>


@endsection