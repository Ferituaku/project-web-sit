@extends('mahasiswa.mainMhs')
@section('title', 'Dashboard Mahasiswa')

@section('content')
<!-- Page Content -->
<div class="container-fluid py-4" style="margin-top: 50px; margin-left:10px">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard Mahasiswa</li>
        </ol>
    </nav>
    <div class="row g-4">
        <!-- User Info Card -->
        <div class="col-md-8">
            <div class="card h-100" style="border:none; border-radius: 1rem; background: rgb(99,181,255);
background: linear-gradient(90deg, rgba(99,181,255,1) 0%, rgba(39,123,255,1) 44%, rgba(0,177,255,1) 100%);">
                <div class=" row card-body">
                    <div class="col-sm-4">
                        <img src="{{ asset('img/user.jpg') }}" alt="avatar" class="rounded-circle img-fluid mb-3" style="width: 200px; margin:2rem">
                    </div>
                    <div class="col-sm-4" style="margin-left:4rem; margin-top: 3.5rem;">
                        <h4>{{ auth()->user()->name }}</h4>
                        <p class="text-light mb-1">{{ auth()->user()->email }}</p>
                        <p class="text-light mb-1">NIM: {{auth()->user()->nip}}</p>
                        <p class="text-light mb-1">No. Telp: - </p>
                        <p class="text-light mb-0">Alamat: - - -</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Card -->
        <div class="col-md-4">
            <div class="card h-100" style="border:none; border-radius: 1rem; background: rgb(99,181,255);
background: linear-gradient(90deg, rgba(99,181,255,1) 0%, rgba(39,123,255,1) 44%, rgba(0,177,255,1) 100%);


">
                <div class="card-body">
                    <div id="calendar" style="width: 345px; height: 240px;">
                        <table class="table-condensed table-bordered table-striped table-light" style="width: 100%; height: 100%;">
                            <thead>
                                <tr>
                                    <th colspan="7">
                                        <span class="btn-group">
                                            <a class="btn"><i class="icon-chevron-left"></i></a>
                                            <a class="btn active" style="padding:2px 80px 2px 80px;">Desember 2024</a>
                                            <a class="btn"><i class="icon-chevron-right"></i></a>
                                        </span>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Su</th>
                                    <th>Mo</th>
                                    <th>Tu</th>
                                    <th>We</th>
                                    <th>Th</th>
                                    <th>Fr</th>
                                    <th>Sa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="muted">29</td>
                                    <td class="muted">30</td>
                                    <td class="muted">31</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>11</td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td>13</td>
                                    <td>14</td>
                                    <td>15</td>
                                    <td>16</td>
                                    <td>17</td>
                                    <td>18</td>
                                </tr>
                                <tr>
                                    <td>19</td>
                                    <td class="btn-primary"><strong>20</strong></td>
                                    <td>21</td>
                                    <td>22</td>
                                    <td>23</td>
                                    <td>24</td>
                                    <td>25</td>
                                </tr>
                                <tr>
                                    <td>26</td>
                                    <td>27</td>
                                    <td>28</td>
                                    <td>29</td>
                                    <td class="muted">1</td>
                                    <td class="muted">2</td>
                                    <td class="muted">3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Status Card -->
        <div class="col-md-8">
            <div class="card" style="border:none;  border-radius: 1rem; background: rgb(99,181,255);
background: linear-gradient(90deg, rgba(99,181,255,1) 0%, rgba(39,123,255,1) 44%, rgba(0,177,255,1) 100%);


">
                <div class="card-body text-center mb-2">
                    <h2 class="mb-4">Status Akademik</h2>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Semester Akademik Sekarang</h5>
                            <p>2024/2025 Ganjil</p>
                        </div>
                        <div class="col-md-4">
                            <h5>Semester</h5>
                            <p>5</p>
                        </div>
                        <div class="col-md-4">
                            <h5>Status</h5>
                            <span class="badge bg-success">AKTIF</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GPA and Credits Card -->
        <div class="col-md-4">
            <div class="card" style="border:none; border-radius: 1rem; background: rgb(99,181,255);
background: linear-gradient(90deg, rgba(99,181,255,1) 0%, rgba(39,123,255,1) 44%, rgba(0,177,255,1) 100%);


">
                <div class="card-body text-center">
                    <h4 class="mb-1">IPK</h4>
                    <p class="h2 mb-2">3.7</p>
                    <h4 class="mb-1">SKS</h4>
                    <p class="h2">90</p>
                </div>
            </div>
        </div>

    </div>
</div>



@endsection