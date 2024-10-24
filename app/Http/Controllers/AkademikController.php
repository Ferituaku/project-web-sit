<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class AkademikController extends Controller
{
    public function akademik()
    {
        return view('akademik/dashboard');
    }
}
