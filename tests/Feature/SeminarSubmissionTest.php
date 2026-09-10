<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\FinalProject;
use App\Models\Supervisor;
use App\Models\SeminarProposal;

class SeminarSubmissionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_student_can_submit_seminar_proposal()
    {
        Storage::fake('public');

        $studentUser = User::factory()->create(['role' => 'MAHASISWA']);
        $student = Student::factory()->create(['user_id' => $studentUser->id]);

        $lecturerUser1 = User::factory()->create(['role' => 'DOSEN']);
        $lecturer1 = Lecturer::factory()->create(['user_id' => $lecturerUser1->id]);
        $lecturerUser2 = User::factory()->create(['role' => 'DOSEN']);
        $lecturer2 = Lecturer::factory()->create(['user_id' => $lecturerUser2->id]);

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

        Supervisor::create(['final_project_id' => $finalProject->id, 'lecturer_id' => $lecturer1->id, 'type' => 'PEMBIMBING_1']);
        Supervisor::create(['final_project_id' => $finalProject->id, 'lecturer_id' => $lecturer2->id, 'type' => 'PEMBIMBING_2']);

        $response = $this->actingAs($studentUser)->post(route('student.seminars.store'), [
            'final_project_id' => $finalProject->id,
            'proposal_file' => UploadedFile::fake()->create('proposal.pdf', 500, 'application/pdf'),
            'student_note' => 'Mohon diproses',
        ]);

        $response->assertRedirect(route('student.seminars.index'));

        $this->assertDatabaseHas('seminar_proposals', [
            'final_project_id' => $finalProject->id,
            'status' => 'WAITING_APPROVAL',
            'student_note' => 'Mohon diproses',
        ]);

        $proposal = SeminarProposal::where('final_project_id', $finalProject->id)->first();
        $this->assertNotNull($proposal);
        Storage::disk('public')->assertExists($proposal->proposal_file);
    }
}
