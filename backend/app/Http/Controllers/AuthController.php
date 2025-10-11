<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showAuth()
    {
        return view('auth.index');
    }

    public function showSignup()
    {
        return redirect()->route('auth');
    }

    public function showLogin()
    {
        return redirect()->route('auth');
    }
}
