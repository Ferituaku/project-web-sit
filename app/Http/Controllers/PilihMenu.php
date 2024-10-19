<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PilihMenu extends Controller
{
    public function pilihmenu()
    {
        return view('/pilihmenu');
    }
}
