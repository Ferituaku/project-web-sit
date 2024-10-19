<?php

namespace App\Http\Controllers;

abstract class AkademikController extends Controller
{
    public function akademik()
    {
        return view('akademik/dashboard');
    }
}
