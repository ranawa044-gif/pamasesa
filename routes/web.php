<?php

use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\FinalDefenseAutoScheduleController;
use App\Http\Controllers\Admin\FinalDefenseController as AdminFinalDefenseController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\SeminarMonitoringController;
use App\Http\Controllers\Admin\SeminarScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SupervisorController;
use App\Http\Controllers\Admin\AdminPengajuanController;
use App\Http\Controllers\Admin\TitleValidationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuidanceCommentController;
use App\Http\Controllers\Lecturer\GuidanceController;
use App\Http\Controllers\Lecturer\SeminarController as LecturerSeminarController;
use App\Http\Controllers\Lecturer\SeminarExecutionController;
use App\Http\Controllers\Student\FinalDefenseController as StudentFinalDefenseController;
use App\Http\Controllers\Student\FinalProjectController;
use App\Http\Controllers\Student\PengajuanController;
use App\Http\Controllers\Student\ProgressLogController;
use App\Http\Controllers\Student\SeminarController as StudentSeminarController;
use App\Http\Controllers\Admin\LecturerImportController;
use App\Http\Controllers\Admin\StudentImportController;
use App\Http\Controllers\Student\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::redirect('/landing', '/');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.attempt');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('guidance-comments', [GuidanceCommentController::class, 'store'])->name('guidance-comments.store');
    Route::get('announcements/{announcement}/download', [AnnouncementController::class, 'download'])->name('admin.announcements.download');
    Route::get('student/pengajuan/{id}/export-pdf', [PengajuanController::class, 'exportPdf'])->name('student.pengajuan.export-pdf');

    Route::middleware('role:ADMIN')->prefix('admin')->name('admin.')->group(function (): void {
        Route::resource('students', StudentController::class)->except('show');
        Route::get('students/import', [StudentImportController::class, 'importForm'])->name('students.import.form');
        Route::post('students/import', [StudentImportController::class, 'import'])->name('students.import');
        Route::get('students/import/template', [StudentImportController::class, 'downloadTemplate'])->name('students.import.template');

        Route::resource('lecturers', LecturerController::class)->except('show');
        Route::get('lecturers/import', [LecturerImportController::class, 'importForm'])->name('lecturers.import.form');
        Route::post('lecturers/import', [LecturerImportController::class, 'import'])->name('lecturers.import');
        Route::get('lecturers/import/template', [LecturerImportController::class, 'downloadTemplate'])->name('lecturers.import.template');

        Route::get('titles', [AdminPengajuanController::class, 'index'])->name('titles.index');
        Route::get('titles/{id}', [AdminPengajuanController::class, 'show'])->name('titles.show');
        Route::put('titles/{id}/status', [AdminPengajuanController::class, 'updateStatus'])->name('titles.update-status');
        Route::get('pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::put('pengajuan/{id}/status', [AdminPengajuanController::class, 'updateStatus'])->name('pengajuan.update-status');
        Route::get('supervisors', [SupervisorController::class, 'index'])->name('supervisors.index');
        Route::get('supervisors/{finalProject}/edit', [SupervisorController::class, 'edit'])->name('supervisors.edit');
        Route::put('supervisors/{finalProject}', [SupervisorController::class, 'update'])->name('supervisors.update');
        Route::resource('announcements', AnnouncementController::class)->except('show');
        Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
        Route::get('backup/download', [BackupController::class, 'download'])->name('backup.download');
    });

    Route::middleware('role:MAHASISWA')->prefix('student')->name('student.')->group(function (): void {
        Route::get('pengajuan', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('final-project', [PengajuanController::class, 'create'])->name('final-project.edit');
        Route::post('final-project', [PengajuanController::class, 'store'])->name('final-project.store');
        Route::get('progress', [ProgressLogController::class, 'index'])->name('progress.index');
        Route::get('progress/create', [ProgressLogController::class, 'create'])->name('progress.create');
        Route::post('progress', [ProgressLogController::class, 'store'])->name('progress.store');
        // Seminar Proposal
        Route::get('seminars', [StudentSeminarController::class, 'index'])->name('seminars.index');
        Route::get('seminars/create', [StudentSeminarController::class, 'create'])->name('seminars.create');
        Route::post('seminars', [StudentSeminarController::class, 'store'])->name('seminars.store');
        Route::post('seminars/revisions/{seminarRevision}/complete', [StudentSeminarController::class, 'completeRevision'])->name('seminars.revisions.complete');
        Route::get('final-defenses', [StudentFinalDefenseController::class, 'index'])->name('final-defenses.index');
        Route::post('final-defenses', [StudentFinalDefenseController::class, 'store'])->name('final-defenses.store');
        // Pengaturan Akun
        Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::put('profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    });

    Route::middleware('role:DOSEN')->prefix('lecturer')->name('lecturer.')->group(function (): void {
        Route::get('guidances', [GuidanceController::class, 'index'])->name('guidances.index');
        Route::get('guidances/{finalProject}', [GuidanceController::class, 'show'])->name('guidances.show');
        Route::put('progress/{progressLog}/review', [GuidanceController::class, 'review'])->name('progress.review');
        Route::put('guidances/{finalProject}/approve', [GuidanceController::class, 'approveDefense'])->name('guidances.approve');
        Route::put('guidances/{finalProject}/acc-seminar', [GuidanceController::class, 'accSeminarProposal'])->name('guidances.acc-seminar');
        // Seminar proposal review
        Route::get('seminars', [LecturerSeminarController::class, 'index'])->name('seminars.index');
        Route::get('seminars/{seminarProposal}', [LecturerSeminarController::class, 'show'])->name('seminars.show');
        Route::put('seminars/{seminarProposal}/review', [LecturerSeminarController::class, 'review'])->name('seminars.review');
        Route::get('seminars/{seminarSchedule}/start', [SeminarExecutionController::class, 'show'])->name('seminars.start');
        Route::post('seminars/{seminarSchedule}/assess', [SeminarExecutionController::class, 'assess'])->name('seminars.assess');
        Route::post('seminars/{seminarSchedule}/revisions', [SeminarExecutionController::class, 'revision'])->name('seminars.revisions.store');
        Route::put('seminars/revisions/{seminarRevision}/validate', [SeminarExecutionController::class, 'reviewRevision'])->name('seminars.revisions.validate');
    });

    Route::middleware('role:ADMIN')->prefix('admin')->name('admin.')->group(function (): void {
        // Seminar scheduling
        Route::get('seminars', [SeminarScheduleController::class, 'index'])->name('seminars.index');
        Route::get('seminars/{seminarProposal}/schedule', [SeminarScheduleController::class, 'create'])->name('seminars.schedule.create');
        Route::post('seminars/schedule', [SeminarScheduleController::class, 'store'])->name('seminars.schedule.store');
        // Auto scheduling
        Route::get('seminars/auto', [App\Http\Controllers\Admin\SeminarAutoScheduleController::class, 'form'])->name('seminars.auto.form');
        Route::post('seminars/auto/preview', [App\Http\Controllers\Admin\SeminarAutoScheduleController::class, 'preview'])->name('seminars.auto.preview');
        Route::post('seminars/auto/publish', [App\Http\Controllers\Admin\SeminarAutoScheduleController::class, 'publish'])->name('seminars.auto.publish');
        Route::get('final-defenses/auto', [FinalDefenseAutoScheduleController::class, 'form'])->name('final-defenses.auto.form');
        Route::post('final-defenses/auto/preview', [FinalDefenseAutoScheduleController::class, 'preview'])->name('final-defenses.auto.preview');
        Route::post('final-defenses/auto/publish', [FinalDefenseAutoScheduleController::class, 'publish'])->name('final-defenses.auto.publish');
        Route::get('seminars/monitor', [SeminarMonitoringController::class, 'index'])->name('seminars.monitor');
        Route::get('seminars/{seminarSchedule}/report', [SeminarMonitoringController::class, 'report'])->name('seminars.report');
        Route::get('final-defenses', [AdminFinalDefenseController::class, 'index'])->name('final-defenses.index');
        Route::put('final-defenses/{finalDefense}/assign', [AdminFinalDefenseController::class, 'assign'])->name('final-defenses.assign');
        Route::view('workflow', 'admin.workflow')->name('workflow');
    });
});
