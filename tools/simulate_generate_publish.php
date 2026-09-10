<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Room;
use App\Services\SeminarSchedulerService;

$rooms = Room::pluck('id')->take(2)->all();
$params = [
    'start_date' => date('Y-m-d'),
    'end_date' => date('Y-m-d', strtotime('+2 days')),
    'start_time' => '08:00',
    'end_time' => '17:00',
    'duration_minutes' => 60,
    'break_minutes' => 15,
    'room_ids' => $rooms,
    'regenerate' => false,
];

$svc = new SeminarSchedulerService();

echo "First generate:\n";
$result = $svc->generate($params);
echo "Assigned: " . count($result['assigned']) . ", Failed: " . count($result['failed']) . "\n";

try {
    $created = $svc->publish(array_map(function($a){ return ['proposal_id'=>$a['proposal_id'],'date'=>$a['date'],'start_time'=>$a['start_time'],'end_time'=>$a['end_time'],'room_id'=>$a['room_id']]; }, $result['assigned']));
    echo "Published: " . count($created) . "\n";
} catch (Exception $e) {
    echo "Publish failed: " . $e->getMessage() . "\n";
}

echo "Second generate (should not create duplicates):\n";
$result2 = $svc->generate($params);
echo "Assigned: " . count($result2['assigned']) . ", Failed: " . count($result2['failed']) . "\n";

try {
    $created2 = $svc->publish(array_map(function($a){ return ['proposal_id'=>$a['proposal_id'],'date'=>$a['date'],'start_time'=>$a['start_time'],'end_time'=>$a['end_time'],'room_id'=>$a['room_id']]; }, $result2['assigned']));
    echo "Published 2: " . count($created2) . "\n";
} catch (Exception $e) {
    echo "Publish 2 failed: " . $e->getMessage() . "\n";
}
