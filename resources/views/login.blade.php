@extends('layout')
@section('title', 'Login')
@section('login')

<section class="vh-100 background w-full" style="background-image: url('/img/back-ground-log-sign.jpg'); background-size: cover; background-position: center;">
  <div class="container h-100 d-flex justify-content-center align-items-center">
    <div class="row justify-content-center align-items-center w-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card text-white" style="border:none; border-radius: 1rem; backdrop-filter: blur(10px); background: rgba(67, 126, 255, 0.5);">
          <div class="card-body p-5 text-center">
            <!-- Logo Section -->
            <section class="vh-10 mb-4 " style="background-image: url('/img/Universitas-Diponegoro-Semarang-Logo.png'); background-size: contain; background-repeat: no-repeat; background-position: center; height: 60px;"></section>
            <div class="mb-md-5 mt-md-4 pb-2">
              <p class="h2 fw-bold mb-1 text-uppercase">Login SIT</p>
              <p class="h4 mb-4 fw-lighter">Sistem Informasi Terintegrasi</p>
              <p class="text-white-50 mb-1">Masukkan Email/NIS/NIP dan Password Anda!</p>

              <!-- Displaying Errors -->
              <div class="mt-5" style="margin-top: 2px;">
                @if($errors->any())
                <div class="col-12">
                  @foreach ($errors->all() as $error )
                  <div class="alert alert-danger">
                    {{ $error }}
                  </div>
                  @endforeach
                </div>
                @endif

                @if (session()->has('error'))
                <div class="alert alert-danger">
                  {{session('error') }}
                </div>
                @endif
                @if (session()->has('success'))
                <div class="alert alert-success">
                  {{session('success') }}
                </div>
                @endif
              </div>

              <!-- Form Login -->
              <form action="{{route('login.post')}}" method="post" class="ms-auto me-auto mt-auto">
                @csrf
                <div class="form-outline form-white mb-4">
                  <input type="text" name="email" id="typeEmailX" class="form-control form-control-lg" placeholder="Email/NIM/NIP" />
                </div>

                <div class="form-outline form-white mb-4">
                  <input type="password" name="password" id="typePasswordX" class="form-control form-control-lg" placeholder="Password" />
                </div>

                <p class="small mb-3">
                  <a class="text-white-50" href="register">Forgot password?</a>
                </p>

                <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  @endsection