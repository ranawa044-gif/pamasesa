<?php

namespace App\Services;

use App\Models\SeminarProposal;
use App\Models\SeminarSchedule;
use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SeminarSchedulerService
{
    /**
     * Generate schedule slots and assign proposals using greedy algorithm.
     * Returns array with 'assigned' and 'failed'.
     *
     * $params: start_date, end_date, start_time, end_time, duration_minutes, break_minutes, room_ids
     */
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

        $proposals = SeminarProposal::with('finalProject.supervisors.lecturer')
            ->whereIn('status', ['READY_TO_SCHEDULE', 'APPROVED_BY_SUPERVISORS'])
            ->get()
            ->values();

        // build all slots
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

        // existing schedules in the date range
        $existingSchedules = SeminarSchedule::with('seminarProposal.finalProject.supervisors')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        // if regenerate flag set, remove DRAFT schedules in the date range and for selected rooms
        if (! empty($params['regenerate'])) {
            $query = SeminarSchedule::where('status', 'DRAFT')
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);
            if (! empty($roomIds)) {
                $query->whereIn('room_id', $roomIds);
            }
            $query->delete();

            // reload existing schedules after deletion
            $existingSchedules = SeminarSchedule::with('seminarProposal.finalProject.supervisors')
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->get();
        }

        $assigned = [];
        $failed = [];

        // keep track of used slots and lecturer/student occupancy for this run
        $usedSlots = [];
        $lecturerBusy = [];
        $studentScheduled = [];

        foreach ($proposals as $proposal) {
            // skip if this proposal already has a DRAFT or PUBLISHED schedule
            $hasProposalSchedule = SeminarSchedule::where('seminar_proposal_id', $proposal->id)
                ->whereIn('status', ['DRAFT', 'PUBLISHED'])
                ->exists();
            if ($hasProposalSchedule) {
                $failed[] = ['proposal' => $proposal, 'reason' => 'Mahasiswa sudah memiliki jadwal seminar proposal'];
                continue;
            }

            // skip if the student (final project) already has any DRAFT or PUBLISHED schedule
            $hasStudentSchedule = SeminarSchedule::whereHas('seminarProposal', function ($q) use ($proposal) {
                $q->where('final_project_id', $proposal->final_project_id);
            })->whereIn('status', ['DRAFT', 'PUBLISHED'])->exists();
            if ($hasStudentSchedule) {
                $failed[] = ['proposal' => $proposal, 'reason' => 'Mahasiswa sudah memiliki jadwal seminar proposal'];
                continue;
            }

            $scheduled = false;

            foreach ($slots as $idx => $slot) {
                if (isset($usedSlots[$idx])) {
                    continue; // slot already taken
                }

                // check room conflicts against existing schedules
                $conflict = $existingSchedules->first(function ($ex) use ($slot) {
                    return $ex->room_id == $slot['room_id'] && $this->overlaps($ex->start_time, $ex->end_time, $slot['start_time'], $slot['end_time']) && $ex->date === $slot['date'];
                });

                if ($conflict) {
                    continue;
                }

                // check lecturers conflict (supervisors)
                $supervisors = $proposal->finalProject->supervisors->pluck('lecturer_id')->filter()->all();
                $lecturerConflict = false;
                foreach ($supervisors as $lectId) {
                    // existing schedules
                    $existsBusy = $existingSchedules->first(function ($ex) use ($lectId, $slot) {
                        $supIds = $ex->seminarProposal->finalProject->supervisors->pluck('lecturer_id')->filter()->all();
                        if (! in_array($lectId, $supIds, true)) return false;
                        return $ex->date === $slot['date'] && $this->overlaps($ex->start_time, $ex->end_time, $slot['start_time'], $slot['end_time']);
                    });
                    if ($existsBusy) { $lecturerConflict = true; break; }

                    // busy in this run
                    if (! empty($lecturerBusy[$lectId])) {
                        foreach ($lecturerBusy[$lectId] as $busy) {
                            if ($busy['date'] === $slot['date'] && $this->overlaps($busy['start_time'], $busy['end_time'], $slot['start_time'], $slot['end_time'])) {
                                $lecturerConflict = true; break 2;
                            }
                        }
                    }
                }

                if ($lecturerConflict) continue;

                // check student already scheduled or conflict
                $studentId = $proposal->final_project_id;
                // existing schedules for this student (any schedule in the date range)
                $studentEx = $existingSchedules->first(function ($ex) use ($studentId, $slot) {
                    return $ex->seminar_proposal_id && $ex->seminarProposal->final_project_id === $studentId && $ex->date === $slot['date'] && $this->overlaps($ex->start_time, $ex->end_time, $slot['start_time'], $slot['end_time']);
                });
                if ($studentEx) continue;

                if (isset($studentScheduled[$studentId])) {
                    continue; // already scheduled in this run
                }

                // passed all checks, assign
                $usedSlots[$idx] = true;
                $assigned[] = [
                    'proposal_id' => $proposal->id,
                    'final_project_id' => $proposal->final_project_id,
                    'date' => $slot['date'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'room_id' => $slot['room_id'],
                    'proposal' => $proposal,
                ];

                // mark lecturers busy
                foreach ($supervisors as $lectId) {
                    $lecturerBusy[$lectId][] = ['date' => $slot['date'], 'start_time' => $slot['start_time'], 'end_time' => $slot['end_time']];
                }

                $studentScheduled[$studentId] = true;
                $scheduled = true;
                break;
            }

            if (! $scheduled) {
                $failed[] = ['proposal' => $proposal, 'reason' => 'Tidak menemukan slot yang sesuai'];
            }
        }

        return ['assigned' => $assigned, 'failed' => $failed, 'slots_count' => count($slots)];
    }

    public function publish(array $assignments): array
    {
        // validate duplicates in provided assignments
        $proposalIds = array_map(fn($x) => $x['proposal_id'], $assignments);
        $duplicateProposals = array_unique(array_diff_assoc($proposalIds, array_unique($proposalIds)));
        if (! empty($duplicateProposals)) {
            throw new \Exception('Duplicate seminar_proposal_id found in assignments: ' . implode(',', $duplicateProposals));
        }

        // validate DB does not already contain DRAFT/PUBLISHED schedules for these proposals or their students
        foreach ($assignments as $a) {
            $proposalId = $a['proposal_id'];

            $existsProposal = SeminarSchedule::where('seminar_proposal_id', $proposalId)
                ->whereIn('status', ['DRAFT', 'PUBLISHED'])
                ->exists();
            if ($existsProposal) {
                throw new \Exception('Publish blocked: proposal already has a schedule (proposal_id: ' . $proposalId . ')');
            }

            // check student
            $proposal = SeminarProposal::find($proposalId);
            if ($proposal) {
                $hasStudent = SeminarSchedule::whereHas('seminarProposal', function ($q) use ($proposal) {
                    $q->where('final_project_id', $proposal->final_project_id);
                })->whereIn('status', ['DRAFT', 'PUBLISHED'])->exists();

                if ($hasStudent) {
                    throw new \Exception('Publish blocked: mahasiswa sudah memiliki jadwal seminar proposal');
                }
            }
        }

        $created = [];
        foreach ($assignments as $a) {
            $schedule = SeminarSchedule::create([
                'seminar_proposal_id' => $a['proposal_id'],
                'date' => $a['date'],
                'start_time' => $a['start_time'],
                'end_time' => $a['end_time'],
                'room_id' => $a['room_id'],
                'status' => 'PUBLISHED',
            ]);

            $proposal = SeminarProposal::find($a['proposal_id']);
            if ($proposal) {
                $proposal->update(['status' => 'SCHEDULED']);
            }

            $created[] = $schedule;
        }

        return $created;
    }

    private function overlaps($aStart, $aEnd, $bStart, $bEnd): bool
    {
        $aS = Carbon::createFromFormat('H:i:s', strlen($aStart) <= 5 ? $aStart.':00' : $aStart);
        $aE = Carbon::createFromFormat('H:i:s', strlen($aEnd) <= 5 ? $aEnd.':00' : $aEnd);
        $bS = Carbon::createFromFormat('H:i:s', strlen($bStart) <= 5 ? $bStart.':00' : $bStart);
        $bE = Carbon::createFromFormat('H:i:s', strlen($bEnd) <= 5 ? $bEnd.':00' : $bEnd);

        return $aS->lt($bE) && $bS->lt($aE);
    }
}
