<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    public function index(): View
    {
        $archives = Registration::with(['student', 'division'])
            ->whereIn('application_status', ['completed', 'rejected'])
            ->latest('updated_at')
            ->get();

        return view('admin.archive.create', compact('archives'));
    }
}
