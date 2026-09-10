# Sprint 4B — Auto Scheduling (Sidang Akhir)

## Goal
Design and prepare the auto-scheduling feature for Final Defense (Sidang Akhir). This document captures requirements, constraints, data references, and a high-level algorithm for the scheduler.

## Important notes
- Existing test data for `final_defenses` used by Sprint 4A is retained and must not be removed.
- No production code changes are made in this sprint preparation; this is a spec and checklist.

## Inputs
- `final_defenses` rows with `status = READY_TO_SCHEDULE` (candidates to be scheduled)
- `examiners` (who are assigned/available for each `final_defense`)
- `rooms` (id, capacity, availability flags)
- `lecturers` (availability info to be recorded or computed)
- University constraints: working hours, excluded dates, parallel session limits

## Data model references
- `final_defenses`: id, final_project_id, status, submitted_at
- `examiners`: final_defense_id, lecturer_id, type (PENGUJI_1/PENGUJI_2), assigned_at
- `seminar_schedules`: seminar_proposal_id, date, start_time, end_time, room_id, status
- `rooms`: id, name, capacity, is_active

## Constraints and rules
- Do not schedule if any mandatory examiner or supervisor is missing.
- Each final defense needs one room and a time slot.
- A lecturer cannot be assigned to two defenses at the same time (respect examiners + supervisors if they must attend).
- Avoid scheduling examiners who are also supervisors for the same project.
- Respect room capacity and activity status.
- Time slots should be configurable (e.g., 60 minutes default).
- Prioritize earliest `submitted_at` or custom priority rules.

## High-level algorithm (greedy baseline)
1. Collect unscheduled `final_defenses` with `status = READY_TO_SCHEDULE`.
2. For each candidate (ordered by priority):
   - Retrieve its examiners (two lecturers recommended).
   - Gather candidate time slots (working days/times) and available rooms.
   - Find the earliest time slot where neither examiner nor (optionally) supervisors are busy, and a room is free.
   - Reserve the slot and create a `seminar_schedule` row (or a new `final_defense_schedules` table) and set `status` to `SCHEDULED`.
3. If conflicts prevent scheduling within a window, move candidate to backlog or notify admin.

## Advanced algorithm ideas
- Use bipartite matching / constraint solver (ILP) to optimize room/time assignment for many candidates.
- Use heuristics: cluster by lecturer availability, minimize lecturer travel time, balance daily load.

## Availability tracking
- Minimal approach: allow admins to provide unavailable date ranges per lecturer.
- Enhanced approach: integrate calendar (ICS / Google) for real availability (future improvement).

## Indexing & performance
- Ensure indexes on: `final_defenses.status`, `examiners.final_defense_id`, `examiners.lecturer_id`, `seminar_schedules.date`, `seminar_schedules.room_id`.

## Next steps (implementation plan)
1. Add scheduler service skeleton: `App\Services\FinalDefenseScheduler`.
2. Add integration tests (seed multiple `final_defenses` with examiners and `rooms`).
3. Implement greedy scheduling algorithm; expose admin preview UI.
4. Add conflict resolution and notifications.
5. Iterate with more advanced solver if needed.

## Acceptance criteria for Sprint 4B
- Scheduler can schedule N defenses into available rooms/time slots without violating lecturer conflicts.
- Admin can preview and publish schedule.
- Unit/integration tests cover conflict rules.

---

Created during Sprint 4A cleanup — keeps test data for Sprint 4B work.
