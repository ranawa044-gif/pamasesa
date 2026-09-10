<?php

use Carbon\Carbon;
use App\Models\SeminarSchedule;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Running quick smoke tests for Seminar Scheduling...\n";

$schedules = SeminarSchedule::with(['seminarProposal.finalProject.student', 'seminarProposal.finalProject.supervisors.lecturer', 'room'])->get();

echo "Total seminar_schedules: " . $schedules->count() . "\n";

$rooms = $schedules->pluck('room')->filter()->unique('id')->count();
echo "Total rooms referenced: {$rooms}\n";

$studentsScheduled = $schedules->map(function($s){ return $s->seminarProposal->finalProject->student->id ?? null; })->filter()->unique()->count();
echo "Total students scheduled: {$studentsScheduled}\n";

$conflicts = ['room' => [], 'supervisor' => [], 'student' => []];

// helper overlap
$overlaps = function($aStart, $aEnd, $bStart, $bEnd){
    $aS = Carbon::createFromFormat('H:i:s', strlen($aStart) <=5 ? $aStart.':00' : $aStart);
    $aE = Carbon::createFromFormat('H:i:s', strlen($aEnd) <=5 ? $aEnd.':00' : $aEnd);
    $bS = Carbon::createFromFormat('H:i:s', strlen($bStart) <=5 ? $bStart.':00' : $bStart);
    $bE = Carbon::createFromFormat('H:i:s', strlen($bEnd) <=5 ? $bEnd.':00' : $bEnd);
    return $aS->lt($bE) && $bS->lt($aE);
};

$list = $schedules->values();
for ($i=0; $i < $list->count(); $i++) {
    $a = $list[$i];
    // check relations
    if (! $a->seminarProposal) {
        echo "Schedule {$a->id} missing seminarProposal\n";
    }

    // student multiple
    for ($j=$i+1; $j < $list->count(); $j++) {
        $b = $list[$j];
        // same date
        if ($a->date !== $b->date) continue;

        // room conflict
        if ($a->room_id && $b->room_id && $a->room_id == $b->room_id) {
            if ($overlaps($a->start_time, $a->end_time, $b->start_time, $b->end_time)) {
                $conflicts['room'][] = [ 'a' => $a->id, 'b' => $b->id, 'room_id' => $a->room_id, 'date' => $a->date ];
            }
        }

        // supervisor conflict
        $supA = optional($a->seminarProposal->finalProject->supervisors)->pluck('lecturer_id')->filter()->all();
        $supB = optional($b->seminarProposal->finalProject->supervisors)->pluck('lecturer_id')->filter()->all();
        foreach ($supA as $sA) {
            if (in_array($sA, $supB, true)) {
                if ($overlaps($a->start_time, $a->end_time, $b->start_time, $b->end_time)) {
                    $conflicts['supervisor'][] = ['lecturer_id' => $sA, 'a' => $a->id, 'b' => $b->id, 'date' => $a->date];
                }
            }
        }

        // student multiple schedules
        $studentA = $a->seminarProposal->finalProject->student->id ?? null;
        $studentB = $b->seminarProposal->finalProject->student->id ?? null;
        if ($studentA && $studentA === $studentB) {
            $conflicts['student'][] = ['student_id' => $studentA, 'a' => $a->id, 'b' => $b->id];
        }
    }
}

echo "Conflicts found:\n";
foreach ($conflicts as $k => $v) {
    echo " - {$k}: " . count($v) . "\n";
}

if (count($conflicts['room']) === 0 && count($conflicts['supervisor']) === 0 && count($conflicts['student']) === 0) {
    echo "Smoke test PASSED: no conflicts detected.\n";
    exit(0);
} else {
    echo "Smoke test FAILED: conflicts detected.\n";
    // print details sample
    foreach ($conflicts as $type => $items) {
        if (count($items)) {
            echo strtoupper($type) . " conflicts sample:\n";
            foreach (array_slice($items, 0, 5) as $it) {
                echo json_encode($it) . "\n";
            }
        }
    }
    exit(2);
}
