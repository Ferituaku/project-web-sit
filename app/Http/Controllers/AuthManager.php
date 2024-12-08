<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthManager extends Controller
{
    function login()
    {
        if (Auth::check()) {
            return redirect(route('dashboard'));
        }
        return view('login');
    }

    function loginPost(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email wajib diisi',
                'password.required' => 'Password wajib diisi'
            ]
        );

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $role = Auth::user()->role;
            switch ($role) {
                case 'mahasiswa':
                    return redirect()->intended('/mahasiswa/dashboard');
                case 'dosen':
                    return redirect()->intended('/dosen/dashboard');
                case 'akademik':
                    return redirect()->intended('/akademik/dashboard');
                case 'kaprodi':
                    return redirect()->intended('/pilihmenu');
                case 'dekan':
                    return redirect()->intended('/pilihmenu');
                default:
                    return redirect('/login')->with('error', 'Role tidak dikenali');
            }
        }
        return redirect(route('login'))->with("error", "Email atau password yang dimasukkan tidak sesuai");
    }

    function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
