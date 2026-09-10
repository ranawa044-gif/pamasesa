<?php

namespace App\Http\Controllers;

use App\Models\Examiner;
use App\Models\FinalDefense;
use App\Models\FinalProject;
use App\Models\ProgressLog;
use App\Models\SeminarProposal;
use App\Models\SeminarRevision;
use App\Models\SeminarSchedule;
use App\Models\Student;
use App\Models\PengajuanPa;
use App\Models\Supervisor;
use App\Models\TitleReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        return match ($request->user()->role) {
            'ADMIN' => $this->admin($request),
            'MAHASISWA' => $this->student($request),
            'DOSEN' => $this->lecturer($request),
            default => redirect()->route('login'),
        };
    }

    private function admin(Request $request): View
    {
        $studentsWithoutSupervision = Student::whereHas('finalProject')
            ->whereDoesntHave('finalProject.supervisors')
            ->with('finalProject')
            ->get();

        // Filters for schedules
        $dateFilter = $request->input('date');
        $roomFilter = $request->input('room_id');
        $lecturerFilter = $request->input('lecturer_id');

        $scheduleQuery = \App\Models\SeminarSchedule::with(['seminarProposal.finalProject.student', 'seminarProposal.finalProject.supervisors.lecturer', 'room']);

        if ($dateFilter) {
            $scheduleQuery->where('date', $dateFilter);
        }

        if ($roomFilter) {
            $scheduleQuery->where('room_id', $roomFilter);
        }

        if ($lecturerFilter) {
            $scheduleQuery->whereHas('seminarProposal.finalProject.supervisors', function ($q) use ($lecturerFilter) {
                $q->where('lecturer_id', $lecturerFilter);
            });
        }

        $today = now()->toDateString();
        $todaySchedules = (clone $scheduleQuery)->where('date', $today)->orderBy('start_time')->get();
        $upcomingSchedules = (clone $scheduleQuery)->where('date', '>', $today)->orderBy('date')->orderBy('start_time')->limit(50)->get();

        $rooms = \App\Models\Room::orderBy('name')->get();
        $lecturers = \App\Models\Lecturer::orderBy('nama')->get();

        $seminarPublishedToday = \App\Models\SeminarSchedule::where('status', 'PUBLISHED')->where('date', '<=', now()->toDateString());

        return view('dashboard.admin', [
            'studentCount' => Student::count(),
            'progressAverage' => (int) round(ProgressLog::avg('percentage') ?? 0),
            'studentsWithoutSupervision' => $studentsWithoutSupervision,
            'studentsWithoutSupervisionCount' => $studentsWithoutSupervision->count(),
            'latestProjects' => FinalProject::with('student')->latest()->take(5)->get(),
            'titleReviewAverage' => (int) round(TitleReview::avg('total_score') ?? 0),
            'pendingTitleReviewCount' => FinalProject::where('status', 'SUBMITTED')->count(),
            'seminarStats' => [
                'incoming' => SeminarProposal::where('status', 'WAITING_APPROVAL')->count(),
                'waiting' => SeminarProposal::whereIn('status', ['WAITING_APPROVAL', 'REVISION'])->count(),
                'ready' => SeminarProposal::whereIn('status', ['APPROVED_BY_SUPERVISORS', 'READY_TO_SCHEDULE'])->count(),
                'scheduled' => SeminarProposal::where('status', 'SCHEDULED')->count(),
                'finished' => SeminarProposal::whereIn('status', ['FINISHED', 'SEMINAR_PASSED', 'SEMINAR_REPEAT'])->count(),
                'waitingAssessment' => $seminarPublishedToday->whereHas('seminarProposal')->has('assessments', '<', 2)->count(),
                'passed' => SeminarProposal::where('status', 'SEMINAR_PASSED')->count(),
                'repeat' => SeminarProposal::where('status', 'SEMINAR_REPEAT')->count(),
            ],
            'implementationCount' => FinalProject::whereHas('progressLogs', function ($query) {
                $query->whereIn('progress_type', ['IMPLEMENTATION', 'REVISION_AFTER_SEMINAR']);
            })->count(),
            'readyForDefenseCount' => FinalProject::where('status', 'READY_FOR_DEFENSE')->count(),
            'waitingValidationCount' => SeminarRevision::where('status', 'WAITING_VALIDATION')->count(),
            'finalDefenseIncoming' => FinalDefense::where('status', 'SUBMITTED')->count(),
            'finalDefenseWaitingExaminer' => FinalDefense::where('status', 'WAITING_EXAMINER')->count(),
            'finalDefenseReadyToSchedule' => FinalDefense::where('status', 'READY_TO_SCHEDULE')->count(),
            'finalDefenseFinished' => FinalDefense::whereIn('status', ['FINISHED', 'PASSED'])->count(),
            'todaySchedules' => $todaySchedules,
            'upcomingSchedules' => $upcomingSchedules,
            'roomsFilter' => $rooms,
            'lecturersFilter' => $lecturers,
            'filters' => ['date' => $dateFilter, 'room_id' => $roomFilter, 'lecturer_id' => $lecturerFilter],
        ]);
    }

    private function student(Request $request): View
    {
        $student = $request->user()->student;
        $pengajuanPa = PengajuanPa::with(['detailPerancangan', 'detailImplementasi', 'detailPrestasi', 'user.student'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->first();

        // Sinkronkan otomatis jika sudah mengajukan tetapi belum ada record final_projects
        if ($pengajuanPa && $student && !$student->finalProject) {
            $pengajuanPa->syncToFinalProject();
        }

        $finalProject = $student?->finalProject()->with([
            'titleReview',
            'supervisors.lecturer',
            'progressLogs.comments.user',
            'seminarProposal.schedule.room',
            'seminarProposal.schedule.assessments.lecturer',
            'seminarProposal.schedule.revisions.lecturer',
        ])->first();
        $lastProgress = $finalProject?->progressLogs->first();

        $announcements = \App\Models\Announcement::where('is_active', true)
            ->orderBy('is_pinned', 'desc')
            ->latest()
            ->get();

        return view('dashboard.student', [
            'student' => $student,
            'finalProject' => $finalProject,
            'pengajuanPa' => $pengajuanPa,
            'progressAverage' => (int) round($finalProject?->progressLogs()->avg('percentage') ?? 0),
            'lastProgress' => $lastProgress,
            'announcements' => $announcements,
        ]);
    }

    private function lecturer(Request $request): View
    {
        $lecturer = $request->user()->lecturer;
        $projects = $lecturer?->supervisors()
            ->with('finalProject.seminarProposal.schedule.room')
            ->get()
            ->pluck('finalProject')
            ->filter()
            ->unique('id')
            ->values() ?? collect();

        $projectIds = $projects->pluck('id')->all();
        $latestProgress = ProgressLog::with('finalProject.student')
            ->whereIn('final_project_id', $projectIds)
            ->latest()
            ->take(5)
            ->get();

        // seminars where this lecturer is supervisor 1 or 2 AND have schedules
        $lecturerId = $lecturer?->id;
        $seminarSchedules = \App\Models\SeminarSchedule::with(['seminarProposal.finalProject.student', 'room'])
            ->whereHas('seminarProposal.finalProject.supervisors', function ($q) use ($lecturerId) {
                $q->where('lecturer_id', $lecturerId);
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // count seminars this week
        $startOfWeek = now()->startOfWeek()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();
        $thisWeekCount = $seminarSchedules->whereBetween('date', [$startOfWeek, $endOfWeek])->count();

        return view('dashboard.lecturer', [
            'lecturer' => $lecturer,
            'projects' => $projects,
            'latestProgress' => $latestProgress,
            'seminarSchedules' => $seminarSchedules,
            'thisWeekCount' => $thisWeekCount,
            'revisionValidationCount' => SeminarRevision::where('status', 'WAITING_VALIDATION')
                ->whereHas('seminarSchedule.seminarProposal.finalProject.supervisors', function ($query) use ($lecturerId) {
                    $query->where('lecturer_id', $lecturerId);
                })->count(),
            'readyForDefenseCount' => FinalProject::whereHas('supervisors', function ($query) use ($lecturerId) {
                $query->where('lecturer_id', $lecturerId);
            })->where('status', 'READY_FOR_DEFENSE')->count(),
            'examinerAssignmentCount' => Examiner::where('lecturer_id', $lecturerId)
                ->whereHas('finalDefense', function ($query) {
                    $query->whereIn('status', ['WAITING_EXAMINER', 'READY_TO_SCHEDULE', 'SCHEDULED']);
                })->count(),
        ]);
    }
}
