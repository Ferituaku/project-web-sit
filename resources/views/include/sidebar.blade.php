<!-- 
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
  <a class="navbar-brand" href="#">{{config('app.name')}}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @auth
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('logout')}}">Keluar</a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link" href="{{route('login')}}">Masuk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('register')}}">Daftar</a>
        </li>
        @endauth
    </ul> 
    <span class="navbar-text">@auth{{auth()->user()->name}}@endauth
    
    </span>
    </div>
  </div>
</nav> -->

@auth
<!-- left sidebar -->
<aside class="left-panel" id="sidebar">
  <nav class="fixed-top col-md-2 d-none d-md-block bg-light sidebar  vh-100 bg-white shadow">
    <div class="d-flex flex-column align-items-center p-3 h-100">
      <!-- Bagian Logo -->
      <a href="#" class="navbar-brand d-flex  align-items-center mb-4">
        <img src="{{ asset('img\Universitas-Diponegoro-Semarang-Logo.png') }}" alt="avatar"
          class="img-fluid" style="height: 50px; width: 50px;">
        <span class="fs-5 fw-bold ms-2 " style="color:black;">SIT Undip</span>
      </a>
      <!-- Menu Navigasi -->
      <ul class="nav nav-pills flex-column w-100 mb-auto">
        <li class="nav-item mb-2">
          <a href="#" class="nav-link active d-flex align-items-center p-2">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="#" class="nav-link d-flex align-items-center p-2" style="color: #9197B3;">
            <i class="bi bi-calendar me-2" style="color: #9197B3;"></i>
            Jadwal
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="#" class="nav-link d-flex align-items-center  p-2" style="color: #9197B3;">
            <i class="bi bi-laptop me-2" style="color: #9197B3;"></i>
            Kuliah Online
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="#" class="nav-link d-flex align-items-center p-2" style="color: #9197B3;">
            <i class="bi bi-mortarboard me-2" style="color: #9197B3;"></i>
            Akademik
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="#" class="nav-link d-flex align-items-center  p-2" style="color: #9197B3;">
            <i class="bi bi-wallet2 me-2" style="color: #9197B3;"></i>
            Biaya Kuliah
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="#" class="nav-link d-flex align-items-center p-2" style="color: #9197B3;">
            <i class="bi bi-file-earmark-text me-2" style="color: #9197B3;"></i>
            Her-Registrasi
          </a>
        </li>
      </ul>

      <!-- Logout -->
      <div class="mt-auto">
        <a href="{{ route('logout') }}" class="nav-link text-danger d-flex align-items-center p-2">
          <i class="bi bi-box-arrow-right me-2"></i>
          Log Out
        </a>
      </div>
    </div>
  </nav>
</aside>


@else
<style>
  .nav {
    display: none;
  }
</style>
<ul class="nav nav-pills flex-column w-100 mb-auto">
  <li class="nav-item mb-2">
    <a class="nav-link d-flex align-items-center p-2" href="{{route('login')}}">Masuk</a>
  </li>
  <li class="nav-item mb-2">
    <a class="nav-link d-flex align-items-center p-2" href="{{route('register')}}">Daftar</a>
  </li>
  @endauth
</ul>