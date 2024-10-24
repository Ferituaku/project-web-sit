@section('header')

<!-- Header -->
<header class="bg-white p-3 border-bottom shadow-sm fixed-top" style="margin-left: 250px;">
    <div class="d-flex justify-content-between align-items-center">
        <h3 style="visibility:hidden;">Dashboard Dosen</h3>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('img/budosen.jpg') }}" alt="user" width="32" height="32" class="rounded-circle me-2">
                <span class="text-dark">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item" href="#">Profil</a></li>
                <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">Keluar</a></li>
            </ul>
        </div>
    </div>
</header>


@endsection