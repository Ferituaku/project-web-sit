<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class akademikControl extends Controller
{
    public function akademik()
    {
        return view('akademik/dashboard');
    }
}
