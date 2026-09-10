<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FinalDefense;
use App\Models\FinalDefenseSchedule;
use App\Models\Lecturer;
use App\Models\Room;
use App\Models\Supervisor;
use App\Services\FinalDefenseSchedulerService;

$ready = FinalDefense::with(['finalProject.student', 'finalProject.supervisors.lecturer', 'examiners.lecturer'])
    ->where('status', 'READY_TO_SCHEDULE')
    ->get();

if ($ready->isEmpty()) {
    $scheduled = FinalDefense::with(['finalProject.student', 'finalProject.supervisors.lecturer', 'examiners.lecturer'])
        ->where('status', 'SCHEDULED')
        ->first();
    if ($scheduled) {
        FinalDefenseSchedule::where('final_defense_id', $scheduled->id)->delete();
        $scheduled->update(['status' => 'READY_TO_SCHEDULE']);
        $ready = collect([$scheduled]);
        echo "recovered final_defense_id=" . $scheduled->id . " to READY_TO_SCHEDULE\n";
    }
}

echo "ready_defense_count=" . $ready->count() . "\n";
foreach ($ready as $fd) {
    echo "FD={$fd->id} student=" . $fd->finalProject->student->nama . " supervisors=" . $fd->finalProject->supervisors->count() . " examiners=" . $fd->examiners->count() . "\n";
    foreach ($fd->finalProject->supervisors as $sup) {
        echo "  supervisor=" . $sup->type . ": " . ($sup->lecturer->nama ?? 'n/a') . "\n";
    }
    foreach ($fd->examiners as $ex) {
        echo "  examiner=" . $ex->type . ": " . ($ex->lecturer->nama ?? 'n/a') . "\n";
    }

    if ($fd->finalProject->supervisors->count() === 0) {
        $available = Lecturer::whereNotIn('id', $fd->examiners->pluck('lecturer_id')->filter()->all())->orderBy('nama')->pluck('id')->all();
        if (count($available) >= 2) {
            Supervisor::create([
                'final_project_id' => $fd->final_project_id,
                'lecturer_id' => $available[0],
                'type' => 'PEMBIMBING_1',
                'assigned_at' => now(),
            ]);
            Supervisor::create([
                'final_project_id' => $fd->final_project_id,
                'lecturer_id' => $available[1],
                'type' => 'PEMBIMBING_2',
                'assigned_at' => now(),
            ]);
            echo "  created_supervisors=" . $available[0] . "," . $available[1] . "\n";
        }
    }
}

echo "room_count=" . Room::count() . "\n";

$all = FinalDefense::with(['finalProject.student','finalProject.supervisors.lecturer','examiners.lecturer'])->get();
$statuses = $all->groupBy('status')->map->count();

echo "status_counts=" . json_encode($statuses->toArray()) . "\n";
foreach ($all as $fd) {
    echo "ALL FD={$fd->id} status=" . $fd->status . " final_project=" . $fd->final_project_id . " project_status=" . ($fd->finalProject->status ?? 'n/a') . " supervisors=" . $fd->finalProject->supervisors->count() . " examiners=" . $fd->examiners->count() . "\n";
}
$lecturers = Lecturer::orderBy('nama')->get();
echo "lecturer_count=" . $lecturers->count() . "\n";
foreach ($lecturers as $lect) {
    echo "lecturer=" . $lect->id . " name=" . $lect->nama . "\n";
}

$rooms = Room::pluck('id')->all();
$params = [
    'start_date' => date('Y-m-d'),
    'end_date' => date('Y-m-d'),
    'start_time' => '08:00',
    'end_time' => '16:00',
    'duration_minutes' => 90,
    'break_minutes' => 0,
    'break_start' => '12:00',
    'break_end' => '13:00',
    'room_ids' => $rooms,
    'regenerate' => true,
];

$service = new FinalDefenseSchedulerService();
$result = $service->generate($params);

echo "generated_assigned=" . count($result['assigned']) . "\n";
echo "generated_failed=" . count($result['failed']) . "\n";
echo "generated_slots=" . ($result['slots_count'] ?? 0) . "\n";

$assignments = array_map(function ($assignment) {
    return [
        'final_defense_id' => $assignment['final_defense_id'],
        'date' => $assignment['date'],
        'start_time' => $assignment['start_time'],
        'end_time' => $assignment['end_time'],
        'room_id' => $assignment['room_id'],
    ];
}, $result['assigned']);

foreach ($assignments as $i => $assignment) {
    echo "assigned_{$i} fd=" . $assignment['final_defense_id'] . " date=" . $assignment['date'] . " start=" . $assignment['start_time'] . " end=" . $assignment['end_time'] . " room=" . $assignment['room_id'] . "\n";
}

foreach ($result['failed'] as $i => $failed) {
    echo "failed_{$i} fd=" . ($failed['finalDefense']->id ?? 'n/a') . " reason=" . $failed['reason'] . "\n";
}

try {
    $published = $service->publish($assignments);
    echo "published_count=" . count($published) . "\n";
} catch (Exception $e) {
    echo "publish_error=" . $e->getMessage() . "\n";
    exit(1);
}

$publishedSchedules = FinalDefenseSchedule::with(['finalDefense.finalProject.supervisors.lecturer', 'finalDefense.examiners.lecturer'])
    ->whereDate('date', date('Y-m-d'))
    ->get();

echo "db_schedule_count=" . $publishedSchedules->count() . "\n";

$roomConflicts = 0;
$participantConflicts = 0;
$duplicateStudents = 0;
$studentSchedule = [];

foreach ($publishedSchedules as $i => $schedule) {
    $students = $schedule->finalDefense->final_project_id;
    if (isset($studentSchedule[$students])) {
        $duplicateStudents++;
    }
    $studentSchedule[$students] = true;

    foreach ($publishedSchedules as $j => $other) {
        if ($i >= $j) {
            continue;
        }
        if ($schedule->date !== $other->date) {
            continue;
        }
        if ($schedule->room_id === $other->room_id && overlaps($schedule->start_time, $schedule->end_time, $other->start_time, $other->end_time)) {
            $roomConflicts++;
        }

        $participants = array_unique(array_merge(
            $schedule->finalDefense->finalProject->supervisors->pluck('lecturer_id')->filter()->all(),
            $schedule->finalDefense->examiners->pluck('lecturer_id')->filter()->all()
        ));
        $otherParticipants = array_unique(array_merge(
            $other->finalDefense->finalProject->supervisors->pluck('lecturer_id')->filter()->all(),
            $other->finalDefense->examiners->pluck('lecturer_id')->filter()->all()
        ));
        foreach ($participants as $pid) {
            if (in_array($pid, $otherParticipants, true) && overlaps($schedule->start_time, $schedule->end_time, $other->start_time, $other->end_time)) {
                $participantConflicts++;
                break;
            }
        }
    }
}

echo "room_conflicts=" . $roomConflicts . "\n";
echo "participant_conflicts=" . $participantConflicts . "\n";
echo "duplicate_student_schedules=" . $duplicateStudents . "\n";

echo "final_defense_schedule_ids=" . json_encode($publishedSchedules->pluck('id')->all()) . "\n";

echo "final_defense_count=" . FinalDefense::count() . "\n";
$roomsUsed = $publishedSchedules->pluck('room_id')->unique()->count();
$lecturerIds = collect();
foreach ($publishedSchedules as $schedule) {
    $lecturerIds = $lecturerIds->merge($schedule->finalDefense->finalProject->supervisors->pluck('lecturer_id'));
    $lecturerIds = $lecturerIds->merge($schedule->finalDefense->examiners->pluck('lecturer_id'));
}
$lecturerIds = $lecturerIds->filter()->unique();
echo "rooms_used=" . $roomsUsed . "\n";
echo "lecturers_involved=" . $lecturerIds->count() . "\n";

echo "admin_visible_count=" . FinalDefenseSchedule::where('status','PUBLISHED')->count() . "\n";
$firstSchedule = $publishedSchedules->first();
if ($firstSchedule) {
    $participantLecturers = array_unique(array_merge(
        $firstSchedule->finalDefense->finalProject->supervisors->pluck('lecturer_id')->filter()->all(),
        $firstSchedule->finalDefense->examiners->pluck('lecturer_id')->filter()->all()
    ));
    foreach ($participantLecturers as $lectId) {
        $lectVisible = FinalDefenseSchedule::where('status','PUBLISHED')
            ->where(function ($q) use ($lectId) {
                $q->whereHas('finalDefense.finalProject.supervisors', function ($q2) use ($lectId) {
                    $q2->where('lecturer_id', $lectId);
                })->orWhereHas('finalDefense.examiners', function ($q2) use ($lectId) {
                    $q2->where('lecturer_id', $lectId);
                });
            })->count();
        echo "lecturer_{$lectId}_visible_count=" . $lectVisible . "\n";
    }
    $studentId = $firstSchedule->finalDefense->finalProject->student->id;
    $studentVisible = FinalDefenseSchedule::where('status','PUBLISHED')->whereHas('finalDefense.finalProject', function ($q) use ($studentId) {
        $q->where('student_id', $studentId);
    })->count();
    echo "student_{$studentId}_visible_count=" . $studentVisible . "\n";
}

function overlaps($aStart, $aEnd, $bStart, $bEnd)
{
    $aS = strtotime($aStart);
    $aE = strtotime($aEnd);
    $bS = strtotime($bStart);
    $bE = strtotime($bEnd);

    return $aS < $bE && $bS < $aE;
}
