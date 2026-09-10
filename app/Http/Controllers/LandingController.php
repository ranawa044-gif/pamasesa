<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\FinalProject;
use App\Models\Lecturer;
use App\Models\Student;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $studentCount = Student::count();
        $lecturerCount = Lecturer::count();
        $approvedProjectCount = FinalProject::whereIn('status', ['APPROVED', 'READY_FOR_DEFENSE', 'FINISHED', 'PASSED'])->count();

        $announcements = Announcement::where('is_active', true)
            ->orderBy('is_pinned', 'desc')
            ->latest()
            ->take(6)
            ->get();

        return view('landing', compact(
            'studentCount',
            'lecturerCount',
            'approvedProjectCount',
            'announcements'
        ));
    }
}
