<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SeminarSchedule;
use Illuminate\Support\Collection;

echo "Running duplicate schedule fixer...\n";

$all = SeminarSchedule::orderBy('date')->orderBy('start_time')->get();
$grouped = $all->groupBy('seminar_proposal_id');
$deleted = 0;
foreach ($grouped as $proposalId => $items) {
    if ($items->count() <= 1) continue;
    echo "Found duplicates for proposal {$proposalId}, keeping earliest and deleting others.\n";
    // keep first (earliest)
    $keep = $items->first();
    $toDelete = $items->slice(1);
    foreach ($toDelete as $d) {
        echo " Deleting schedule {$d->id} (date {$d->date} {$d->start_time}-{$d->end_time})\n";
        $d->delete();
        $deleted++;
    }
    // ensure kept proposal status set to SCHEDULED
    $keep->seminarProposal()->update(['status' => 'SCHEDULED']);
}

echo "Deleted {$deleted} duplicate schedules.\n";
