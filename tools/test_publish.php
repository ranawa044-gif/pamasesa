<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SeminarSchedule;
use App\Services\SeminarSchedulerService;

$s = SeminarSchedule::first();
if (! $s) {
    echo "No schedule found\n";
    exit(0);
}
$assign = [[
    'proposal_id' => $s->seminar_proposal_id,
    'date' => $s->date,
    'start_time' => $s->start_time,
    'end_time' => $s->end_time,
    'room_id' => $s->room_id,
]];

$svc = new SeminarSchedulerService();
try {
    $svc->publish($assign);
    echo "Published OK\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
