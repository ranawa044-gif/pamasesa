<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SeminarSchedule;

$schedules = SeminarSchedule::with(['seminarProposal.finalProject.student', 'seminarProposal.finalProject.supervisors.lecturer', 'room'])->get();
foreach ($schedules as $s) {
    $student = $s->seminarProposal->finalProject->student;
    $fps = $s->seminarProposal->finalProject;
    echo "Schedule ID: {$s->id}\n";
    echo "  Proposal ID: {$s->seminar_proposal_id}, Final Project ID: {$s->seminarProposal->final_project_id}\n";
    echo "  Date: {$s->date} {$s->start_time}-{$s->end_time}\n";
    echo "  Room: " . ($s->room?->name ?? $s->room_id) . "\n";
    echo "  Student: " . ($student?->id . ' - ' . $student?->nama ?? '-') . "\n";
    $sup = $fps->supervisors->map(fn($x)=> ($x->lecturer?->nama ?? $x->lecturer_id))->implode(', ');
    echo "  Supervisors: {$sup}\n";
    echo "  Status: {$s->status}\n";
    echo "-------------------------\n";
}
