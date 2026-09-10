<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\FinalProject;
use App\Models\Room;
use App\Models\SeminarProposal;
use App\Models\SeminarSchedule;

class SeminarSchedulingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_schedule_seminar()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);

        $studentUser = User::factory()->create(['role' => 'MAHASISWA']);
        $student = Student::factory()->create(['user_id' => $studentUser->id]);

        $finalProject = FinalProject::create([
            'student_id' => $student->id,
            'title' => 'Test Title',
            'background' => 'bg',
            'research_object' => 'obj',
            'business_process' => 'proc',
            'proposed_supervisor_name' => null,
            'system_type' => 'Web',
            'main_features' => 'features',
            'actor_count' => 2,
            'development_method' => 'Agile',
            'additional_method' => null,
            'technology' => 'PHP',
            'testing_plan' => 'plan',
            'declaration' => true,
            'status' => 'APPROVED',
        ]);

        $room = Room::create(['name' => 'Ruang A']);

        $proposal = SeminarProposal::create([
            'final_project_id' => $finalProject->id,
            'status' => 'READY_TO_SCHEDULE',
            'proposal_file' => 'seminars/proposals/dummy.pdf',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.seminars.schedule.store'), [
            'seminar_proposal_id' => $proposal->id,
            'date' => now()->addWeek()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'room_id' => $room->id,
        ]);

        $response->assertRedirect(route('admin.seminars.index'));

        $this->assertDatabaseHas('seminar_schedules', [
            'seminar_proposal_id' => $proposal->id,
            'room' => 'Ruang A',
        ]);

        $this->assertDatabaseHas('seminar_proposals', [
            'id' => $proposal->id,
            'status' => 'SCHEDULED',
        ]);
    }
}
