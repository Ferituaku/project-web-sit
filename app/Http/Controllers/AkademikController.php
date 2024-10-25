<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

abstract class AkademikController extends Controller
{
    public function akademik()
    {
        return view('akademik/dashboard');
    }
}
