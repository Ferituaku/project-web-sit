<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function mahasiswa()
    {
        return view('mahasiswa/dashboard');
    }
    public function jadwal()
    {
        return view('mahasiswa/jadwal');
    }
    public function akademisi()
    {
        return view('mahasiswa/akademisi');
    }
    public function herreg()
    {
        return view('mahasiswa/herreg');
    }
    public function biaya()
    {
        return view('mahasiswa/biaya');
    }
    public function kulon()
    {
        return view('mahasiswa/kulon');
    }
}
