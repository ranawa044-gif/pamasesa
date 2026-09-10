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

class SeminarReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_lecturer_can_review_seminar_proposal()
    {
        $lecturerUser = User::factory()->create(['role' => 'DOSEN']);
        $lecturer = Lecturer::factory()->create(['user_id' => $lecturerUser->id]);

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

        // assign lecturer as pembimbing 1
        Supervisor::create(['final_project_id' => $finalProject->id, 'lecturer_id' => $lecturer->id, 'type' => 'PEMBIMBING_1']);
        $otherLecturerUser = User::factory()->create(['role' => 'DOSEN']);
        $otherLecturer = Lecturer::factory()->create(['user_id' => $otherLecturerUser->id]);
        Supervisor::create(['final_project_id' => $finalProject->id, 'lecturer_id' => $otherLecturer->id, 'type' => 'PEMBIMBING_2']);

        $proposal = SeminarProposal::create([
            'final_project_id' => $finalProject->id,
            'status' => 'WAITING_APPROVAL',
            'proposal_file' => 'seminars/proposals/dummy.pdf',
        ]);

        $response = $this->actingAs($lecturerUser)->put(route('lecturer.seminars.review', $proposal), [
            'approval' => 'APPROVED',
            'note' => 'Good',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('seminar_proposals', [
            'final_project_id' => $finalProject->id,
            'supervisor_one_approval' => 'APPROVED',
        ]);
    }
}
