<?php
namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function user()
    {
        return view('user.home');
    }

    public function petugas()
    {
        return view('petugas.home');
    }

    public function kepala()
    {
        return view('dashboard.kepala');
    }
}
