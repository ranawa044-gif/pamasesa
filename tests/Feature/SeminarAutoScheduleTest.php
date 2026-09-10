<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\FinalProject;
use App\Models\Supervisor;
use App\Models\SeminarProposal;
use App\Models\Room;

class SeminarAutoScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_auto_schedule_form()
    {
        $adminUser = User::factory()->create(['role' => 'ADMIN']);

        $response = $this->actingAs($adminUser)->get(route('admin.seminars.auto.form'));

        $response->assertStatus(200);
        $response->assertSeeText('Auto Schedule Seminar');
    }

    public function test_admin_can_preview_auto_schedule()
    {
        $adminUser = User::factory()->create(['role' => 'ADMIN']);

        // Create supervisors
        $lecturer1User = User::factory()->create(['role' => 'DOSEN']);
        $lecturer1 = Lecturer::factory()->create(['user_id' => $lecturer1User->id]);

        $lecturer2User = User::factory()->create(['role' => 'DOSEN']);
        $lecturer2 = Lecturer::factory()->create(['user_id' => $lecturer2User->id]);

        // Create students with final projects
        $studentUser1 = User::factory()->create(['role' => 'MAHASISWA']);
        $student1 = Student::factory()->create(['user_id' => $studentUser1->id]);

        $finalProject1 = FinalProject::create([
            'student_id' => $student1->id,
            'title' => 'Test Project 1',
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

        Supervisor::create(['final_project_id' => $finalProject1->id, 'lecturer_id' => $lecturer1->id, 'type' => 'PEMBIMBING_1']);
        Supervisor::create(['final_project_id' => $finalProject1->id, 'lecturer_id' => $lecturer2->id, 'type' => 'PEMBIMBING_2']);

        // Create approved seminar proposal
        $proposal1 = SeminarProposal::create([
            'final_project_id' => $finalProject1->id,
            'status' => 'APPROVED_BY_SUPERVISORS',
            'proposal_file' => 'seminars/proposals/dummy.pdf',
            'supervisor_one_approval' => 'APPROVED',
            'supervisor_two_approval' => 'APPROVED',
        ]);

        // Create room
        $room = Room::create(['name' => 'Ruang A', 'capacity' => 50]);

        // Preview auto schedule
        $response = $this->actingAs($adminUser)->post(route('admin.seminars.auto.preview'), [
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
            'start_time' => '08:00',
            'end_time' => '17:00',
            'duration_minutes' => 60,
            'break_minutes' => 15,
            'room_ids' => [$room->id],
        ]);

        $response->assertStatus(200);
        $response->assertSeeText('auto_preview');
    }

    public function test_auto_schedule_generates_slots_correctly()
    {
        $adminUser = User::factory()->create(['role' => 'ADMIN']);

        $lecturer1User = User::factory()->create(['role' => 'DOSEN']);
        $lecturer1 = Lecturer::factory()->create(['user_id' => $lecturer1User->id]);

        $lecturer2User = User::factory()->create(['role' => 'DOSEN']);
        $lecturer2 = Lecturer::factory()->create(['user_id' => $lecturer2User->id]);

        $studentUser = User::factory()->create(['role' => 'MAHASISWA']);
        $student = Student::factory()->create(['user_id' => $studentUser->id]);

        $finalProject = FinalProject::create([
            'student_id' => $student->id,
            'title' => 'Test Project',
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

        Supervisor::create(['final_project_id' => $finalProject->id, 'lecturer_id' => $lecturer1->id, 'type' => 'PEMBIMBING_1']);
        Supervisor::create(['final_project_id' => $finalProject->id, 'lecturer_id' => $lecturer2->id, 'type' => 'PEMBIMBING_2']);

        $proposal = SeminarProposal::create([
            'final_project_id' => $finalProject->id,
            'status' => 'APPROVED_BY_SUPERVISORS',
            'proposal_file' => 'seminars/proposals/dummy.pdf',
            'supervisor_one_approval' => 'APPROVED',
            'supervisor_two_approval' => 'APPROVED',
        ]);

        $room = Room::create(['name' => 'Ruang A', 'capacity' => 50]);

        $response = $this->actingAs($adminUser)->post(route('admin.seminars.auto.preview'), [
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-01',
            'start_time' => '08:00',
            'end_time' => '10:00',
            'duration_minutes' => 60,
            'break_minutes' => 0,
            'room_ids' => [$room->id],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('seminar_proposals', 1);
        $this->assertDatabaseMissing('seminar_schedules', ['seminar_proposal_id' => $proposal->id]);
    }

    public function test_admin_can_publish_auto_schedule()
    {
        $adminUser = User::factory()->create(['role' => 'ADMIN']);

        $lecturer1User = User::factory()->create(['role' => 'DOSEN']);
        $lecturer1 = Lecturer::factory()->create(['user_id' => $lecturer1User->id]);

        $lecturer2User = User::factory()->create(['role' => 'DOSEN']);
        $lecturer2 = Lecturer::factory()->create(['user_id' => $lecturer2User->id]);

        $studentUser = User::factory()->create(['role' => 'MAHASISWA']);
        $student = Student::factory()->create(['user_id' => $studentUser->id]);

        $finalProject = FinalProject::create([
            'student_id' => $student->id,
            'title' => 'Test Project',
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

        Supervisor::create(['final_project_id' => $finalProject->id, 'lecturer_id' => $lecturer1->id, 'type' => 'PEMBIMBING_1']);
        Supervisor::create(['final_project_id' => $finalProject->id, 'lecturer_id' => $lecturer2->id, 'type' => 'PEMBIMBING_2']);

        $proposal = SeminarProposal::create([
            'final_project_id' => $finalProject->id,
            'status' => 'APPROVED_BY_SUPERVISORS',
            'proposal_file' => 'seminars/proposals/dummy.pdf',
            'supervisor_one_approval' => 'APPROVED',
            'supervisor_two_approval' => 'APPROVED',
        ]);

        $room = Room::create(['name' => 'Ruang A', 'capacity' => 50]);

        $assignments = json_encode([
            [
                'proposal_id' => $proposal->id,
                'final_project_id' => $finalProject->id,
                'date' => '2026-07-01',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'room_id' => $room->id,
            ],
        ]);

        $response = $this->actingAs($adminUser)->post(route('admin.seminars.auto.publish'), [
            'assignments' => $assignments,
        ]);

        $response->assertRedirect(route('admin.seminars.index'));
        $this->assertDatabaseHas('seminar_schedules', [
            'seminar_proposal_id' => $proposal->id,
            'date' => '2026-07-01',
            'start_time' => '08:00',
            'end_time' => '09:00',
            'room_id' => $room->id,
        ]);
        $this->assertDatabaseHas('seminar_proposals', [
            'id' => $proposal->id,
            'status' => 'SCHEDULED',
        ]);
    }

    public function test_auto_schedule_respects_lecturer_conflicts()
    {
        $adminUser = User::factory()->create(['role' => 'ADMIN']);

        $lecturer1User = User::factory()->create(['role' => 'DOSEN']);
        $lecturer1 = Lecturer::factory()->create(['user_id' => $lecturer1User->id]);

        $lecturer2User = User::factory()->create(['role' => 'DOSEN']);
        $lecturer2 = Lecturer::factory()->create(['user_id' => $lecturer2User->id]);

        // Create two students with same lecturer
        $studentUser1 = User::factory()->create(['role' => 'MAHASISWA']);
        $student1 = Student::factory()->create(['user_id' => $studentUser1->id]);

        $finalProject1 = FinalProject::create([
            'student_id' => $student1->id,
            'title' => 'Project 1',
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

        Supervisor::create(['final_project_id' => $finalProject1->id, 'lecturer_id' => $lecturer1->id, 'type' => 'PEMBIMBING_1']);
        Supervisor::create(['final_project_id' => $finalProject1->id, 'lecturer_id' => $lecturer2->id, 'type' => 'PEMBIMBING_2']);

        $studentUser2 = User::factory()->create(['role' => 'MAHASISWA']);
        $student2 = Student::factory()->create(['user_id' => $studentUser2->id]);

        $finalProject2 = FinalProject::create([
            'student_id' => $student2->id,
            'title' => 'Project 2',
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

        Supervisor::create(['final_project_id' => $finalProject2->id, 'lecturer_id' => $lecturer1->id, 'type' => 'PEMBIMBING_1']);
        Supervisor::create(['final_project_id' => $finalProject2->id, 'lecturer_id' => $lecturer2->id, 'type' => 'PEMBIMBING_2']);

        $proposal1 = SeminarProposal::create([
            'final_project_id' => $finalProject1->id,
            'status' => 'APPROVED_BY_SUPERVISORS',
            'proposal_file' => 'seminars/proposals/dummy.pdf',
            'supervisor_one_approval' => 'APPROVED',
            'supervisor_two_approval' => 'APPROVED',
        ]);

        $proposal2 = SeminarProposal::create([
            'final_project_id' => $finalProject2->id,
            'status' => 'APPROVED_BY_SUPERVISORS',
            'proposal_file' => 'seminars/proposals/dummy.pdf',
            'supervisor_one_approval' => 'APPROVED',
            'supervisor_two_approval' => 'APPROVED',
        ]);

        $room = Room::create(['name' => 'Ruang A', 'capacity' => 50]);

        $response = $this->actingAs($adminUser)->post(route('admin.seminars.auto.preview'), [
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-01',
            'start_time' => '08:00',
            'end_time' => '10:00',
            'duration_minutes' => 60,
            'break_minutes' => 0,
            'room_ids' => [$room->id],
        ]);

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_auto_schedule()
    {
        $lecturerUser = User::factory()->create(['role' => 'DOSEN']);

        $response = $this->actingAs($lecturerUser)->get(route('admin.seminars.auto.form'));

        $response->assertStatus(403);
    }
}
