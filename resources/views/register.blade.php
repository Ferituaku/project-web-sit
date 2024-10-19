@extends('layout')
@section('title', 'Register')
@section('login')

<div class="container-sm p-3 position-absolute top-50 start-50 translate-middle mt-1 ">
    <div class="mt-5">
        @if($errors->any())
        <div class="col-12">
            @foreach ($errors->all as $error )
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
    <form action="{{route('register.post')}}" method="post" class="ms-auto me-auto mt-auto " style="width: 500px;">
        @csrf
        <h1>Daftar</h1>
        <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" id="floatingInput" placeholder="">
            <label for="floatingInput">Nama Lengkap</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">NIK</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>
        <button type="submit" class="btn btn-primary position-absolute top-30 start-50 translate-middle mt-4">Submit</button>
        <div>
            <p class="mb-0">Sudah punya akun <a href="/login" class="text-black-50 fw-bold">Masuk</a></p>
        </div>
    </form>

</div>

@endsection