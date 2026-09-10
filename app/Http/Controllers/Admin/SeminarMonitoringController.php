<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeminarSchedule;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeminarMonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $today = now()->toDateString();

        $seminarSchedules = SeminarSchedule::with(['seminarProposal.finalProject.student', 'seminarProposal.finalProject.supervisors.lecturer', 'room', 'assessments.lecturer'])
            ->orderByDesc('date')
            ->orderBy('start_time')
            ->get();

        return view('admin.seminars.monitoring', [
            'seminarSchedules' => $seminarSchedules,
            'stats' => [
                'waitingAssessment' => SeminarSchedule::where('status', 'PUBLISHED')->where('date', '<=', $today)->has('assessments', '<', 2)->count(),
                'finished' => SeminarSchedule::where('status', 'FINISHED')->count(),
                'passed' => SeminarSchedule::whereHas('seminarProposal', fn($q) => $q->where('status', 'SEMINAR_PASSED'))->count(),
                'repeat' => SeminarSchedule::whereHas('seminarProposal', fn($q) => $q->where('status', 'SEMINAR_REPEAT'))->count(),
            ],
        ]);
    }

    public function report(SeminarSchedule $seminarSchedule)
    {
        $seminarSchedule->load(['seminarProposal.finalProject.student', 'seminarProposal.finalProject.supervisors.lecturer', 'room', 'assessments.lecturer', 'revisions.lecturer']);

        if ($seminarSchedule->status !== 'FINISHED') {
            return back()->with('warning', 'Berita acara hanya dapat dibuat untuk seminar yang selesai.');
        }

        if (! class_exists(\Dompdf\Dompdf::class)) {
            return back()->with('warning', 'DomPDF belum terpasang. Tambahkan paket dompdf/dompdf untuk mengaktifkan fitur PDF.');
        }

        $pdfService = new PdfGeneratorService();
        $html = view('admin.seminars.report', ['seminarSchedule' => $seminarSchedule])->render();
        $pdf = $pdfService->generate($html);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="berita-acara-seminar-' . $seminarSchedule->id . '.pdf"',
        ]);
    }
}
