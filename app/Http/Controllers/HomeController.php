<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Logged users go to internal dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Guests see a public landing page
        return view('public.landing');
    }
}
