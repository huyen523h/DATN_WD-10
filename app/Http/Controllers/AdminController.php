<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function tours()
    {
        return view('admin.tours');
    }

    public function bookings()
    {
        return view('admin.bookings');
    }

    public function customers()
    {
        return view('admin.customers');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}


