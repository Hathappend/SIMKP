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

        $archives = Registration::with(['student', 'division', 'assessment'])
        ->whereIn('application_status', ['completed', 'rejected'])
            ->latest('updated_at')
            ->get();

        $stats = [
            'total'     => $archives->count(),
            'completed' => $archives->where('application_status', 'completed')->count(),
            'rejected'  => $archives->where('application_status', 'rejected')->count(),
            'avg_score' => $archives->where('application_status', 'completed')
                    ->avg('assessment.final_score') ?? 0
        ];

        return view('admin.archive.create', compact('archives', 'stats'));
    }
}
