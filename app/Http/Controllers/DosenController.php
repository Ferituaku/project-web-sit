<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function dosen()
    {
        return view('dosen/dashboard');
    }
    public function verifikasi()
    {
        return view('/dosen/verifikasi');
    }
    public function lihatjadwal()
    {
        return view('/dosen/lihatjadwal');
    }
    public function konsultasi()
    {
        return view('/dosen/konsultasi');
    }
}
