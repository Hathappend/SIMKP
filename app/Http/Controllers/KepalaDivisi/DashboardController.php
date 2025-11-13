<?php

namespace App\Http\Controllers\KepalaDivisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('kepala_divisi.dashboard');
    }
}
