<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DekanController extends Controller
{
    public function dekan()
    {
        return view('dekan/dashboard');
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
