<?php

namespace App\Services;

use App\Models\FinalDefense;
use App\Models\FinalDefenseSchedule;
use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class FinalDefenseSchedulerService
{
    public function generate(array $params): array
    {
        $startDate = Carbon::parse($params['start_date'])->startOfDay();
        $endDate = Carbon::parse($params['end_date'])->startOfDay();
        $startTime = Carbon::parse($params['start_time']);
        $endTime = Carbon::parse($params['end_time']);
        $duration = (int) $params['duration_minutes'];
        $break = (int) $params['break_minutes'];
        $roomIds = $params['room_ids'] ?? [];
        $breakStart = ! empty($params['break_start']) ? Carbon::parse($params['break_start']) : null;
        $breakEnd = ! empty($params['break_end']) ? Carbon::parse($params['break_end']) : null;

        $finalDefenses = FinalDefense::with(['finalProject.student', 'finalProject.supervisors.lecturer', 'examiners.lecturer'])
            ->where('status', 'READY_TO_SCHEDULE')
            ->get()
            ->values();

        $slots = [];
        $period = CarbonPeriod::create($startDate, '1 day', $endDate);

        foreach ($period as $day) {
            foreach ($roomIds as $roomId) {
                $cursor = $day->copy()->setTimeFromTimeString($startTime->format('H:i'));
                $dayEnd = $day->copy()->setTimeFromTimeString($endTime->format('H:i'));

                while ($cursor->copy()->addMinutes($duration)->lte($dayEnd)) {
                    $slotStart = $cursor->copy();
                    $slotEnd = $slotStart->copy()->addMinutes($duration);

                    if ($breakStart && $breakEnd) {
                        $breakWindowStart = $day->copy()->setTimeFromTimeString($breakStart->format('H:i'));
                        $breakWindowEnd = $day->copy()->setTimeFromTimeString($breakEnd->format('H:i'));

                        if ($this->overlaps($slotStart->format('H:i'), $slotEnd->format('H:i'), $breakWindowStart->format('H:i'), $breakWindowEnd->format('H:i'))) {
                            if ($cursor->lt($breakWindowEnd)) {
                                $cursor = $breakWindowEnd->copy();
                                continue;
                            }
                        }
                    }

                    $slots[] = [
                        'date' => $slotStart->toDateString(),
                        'start_time' => $slotStart->format('H:i'),
                        'end_time' => $slotEnd->format('H:i'),
                        'room_id' => $roomId,
                    ];

                    $cursor->addMinutes($duration + $break);
                }
            }
        }

        $existingSchedules = FinalDefenseSchedule::with(['finalDefense.finalProject.supervisors', 'finalDefense.examiners'])
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        if (! empty($params['regenerate'])) {
            $query = FinalDefenseSchedule::where('status', 'DRAFT')
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

            if (! empty($roomIds)) {
                $query->whereIn('room_id', $roomIds);
            }

            $query->delete();

            $existingSchedules = FinalDefenseSchedule::with(['finalDefense.finalProject.supervisors', 'finalDefense.examiners'])
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->get();
        }

        $assigned = [];
        $failed = [];
        $usedSlots = [];
        $participantBusy = [];
        $studentScheduled = [];

        foreach ($finalDefenses as $finalDefense) {
            $hasSchedule = FinalDefenseSchedule::where('final_defense_id', $finalDefense->id)
                ->whereIn('status', ['DRAFT', 'PUBLISHED'])
                ->exists();

            if ($hasSchedule) {
                $failed[] = ['finalDefense' => $finalDefense, 'reason' => 'Sidang sudah memiliki jadwal.'];
                continue;
            }

            $participantIds = array_unique(array_merge(
                $finalDefense->finalProject->supervisors->pluck('lecturer_id')->filter()->all(),
                $finalDefense->examiners->pluck('lecturer_id')->filter()->all()
            ));

            $scheduled = false;
            $studentId = $finalDefense->final_project_id;

            foreach ($slots as $idx => $slot) {
                if (isset($usedSlots[$idx])) {
                    continue;
                }

                $roomConflict = $existingSchedules->first(function ($existing) use ($slot) {
                    return $existing->room_id == $slot['room_id'] && $this->overlaps($existing->start_time, $existing->end_time, $slot['start_time'], $slot['end_time']) && $existing->date === $slot['date'];
                });

                if ($roomConflict) {
                    continue;
                }

                $participantConflict = false;
                foreach ($participantIds as $lectId) {
                    $existingBusy = $existingSchedules->first(function ($existing) use ($lectId, $slot) {
                        $participantIds = array_unique(array_merge(
                            $existing->finalDefense->finalProject->supervisors->pluck('lecturer_id')->filter()->all(),
                            $existing->finalDefense->examiners->pluck('lecturer_id')->filter()->all()
                        ));

                        if (! in_array($lectId, $participantIds, true)) {
                            return false;
                        }

                        return $existing->date === $slot['date'] && $this->overlaps($existing->start_time, $existing->end_time, $slot['start_time'], $slot['end_time']);
                    });

                    if ($existingBusy) {
                        $participantConflict = true;
                        break;
                    }

                    if (! empty($participantBusy[$lectId])) {
                        foreach ($participantBusy[$lectId] as $busy) {
                            if ($busy['date'] === $slot['date'] && $this->overlaps($busy['start_time'], $busy['end_time'], $slot['start_time'], $slot['end_time'])) {
                                $participantConflict = true;
                                break 2;
                            }
                        }
                    }
                }

                if ($participantConflict) {
                    continue;
                }

                $studentConflict = $existingSchedules->first(function ($existing) use ($studentId, $slot) {
                    return $existing->finalDefense->final_project_id === $studentId && $existing->date === $slot['date'] && $this->overlaps($existing->start_time, $existing->end_time, $slot['start_time'], $slot['end_time']);
                });

                if ($studentConflict || isset($studentScheduled[$studentId])) {
                    continue;
                }

                $usedSlots[$idx] = true;
                $assigned[] = [
                    'final_defense_id' => $finalDefense->id,
                    'finalDefense' => $finalDefense,
                    'date' => $slot['date'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'room_id' => $slot['room_id'],
                ];

                foreach ($participantIds as $lectId) {
                    $participantBusy[$lectId][] = ['date' => $slot['date'], 'start_time' => $slot['start_time'], 'end_time' => $slot['end_time']];
                }

                $studentScheduled[$studentId] = true;
                $scheduled = true;
                break;
            }

            if (! $scheduled) {
                $failed[] = ['finalDefense' => $finalDefense, 'reason' => 'Tidak ditemukan slot yang cocok.'];
            }
        }

        return ['assigned' => $assigned, 'failed' => $failed, 'slots_count' => count($slots)];
    }

    public function publish(array $assignments): array
    {
        $finalDefenseIds = array_map(fn ($item) => $item['final_defense_id'] ?? null, $assignments);
        $duplicateIds = array_unique(array_diff_assoc($finalDefenseIds, array_unique($finalDefenseIds)));

        if (! empty($duplicateIds)) {
            throw new \Exception('Duplicate final_defense_id found in assignments: ' . implode(',', $duplicateIds));
        }

        foreach ($assignments as $assignment) {
            $finalDefenseId = $assignment['final_defense_id'] ?? null;

            if (! $finalDefenseId) {
                throw new \Exception('Assignment payload missing final_defense_id.');
            }

            $exists = FinalDefenseSchedule::where('final_defense_id', $finalDefenseId)
                ->whereIn('status', ['DRAFT', 'PUBLISHED'])
                ->exists();

            if ($exists) {
                throw new \Exception('Publish blocked: sidang sudah memiliki jadwal (final_defense_id: ' . $finalDefenseId . ').');
            }
        }

        $created = [];

        foreach ($assignments as $assignment) {
            $schedule = FinalDefenseSchedule::create([
                'final_defense_id' => $assignment['final_defense_id'],
                'date' => $assignment['date'],
                'start_time' => $assignment['start_time'],
                'end_time' => $assignment['end_time'],
                'room_id' => $assignment['room_id'],
                'status' => 'PUBLISHED',
            ]);

            $finalDefense = FinalDefense::find($assignment['final_defense_id']);
            if ($finalDefense) {
                $finalDefense->update(['status' => 'SCHEDULED']);
            }

            $created[] = $schedule;
        }

        return $created;
    }

    private function overlaps(string $aStart, string $aEnd, string $bStart, string $bEnd): bool
    {
        $aS = Carbon::createFromFormat('H:i:s', strlen($aStart) <= 5 ? $aStart . ':00' : $aStart);
        $aE = Carbon::createFromFormat('H:i:s', strlen($aEnd) <= 5 ? $aEnd . ':00' : $aEnd);
        $bS = Carbon::createFromFormat('H:i:s', strlen($bStart) <= 5 ? $bStart . ':00' : $bStart);
        $bE = Carbon::createFromFormat('H:i:s', strlen($bEnd) <= 5 ? $bEnd . ':00' : $bEnd);

        return $aS->lt($bE) && $bS->lt($aE);
    }
}
