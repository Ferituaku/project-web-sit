<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaprodiController extends Controller
{
    public function kaprodi()
    {
        return view('kaprodi/dashboard');
    }
    public function buatjadwal()
    {
        return view('kaprodi/buatjadwal');
    }
    public function pilihmenu()
    {
        return view('/pilihmenu');
    }
    public function dosenalter()
    {
        return view('dosen/dashboard');
    }
}
