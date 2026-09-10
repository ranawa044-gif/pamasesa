<?php

namespace Database\Seeders;

use App\Models\FinalProject;
use App\Models\SeminarProposal;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SeminarProposalSeeder extends Seeder
{
    public function run(): void
    {
        // Create seminar proposals for some final projects that have supervisors and no proposal yet
        $projects = FinalProject::whereHas('supervisors')->with('student')->take(10)->get();

        foreach ($projects as $p) {
            if ($p->seminarProposal) {
                continue;
            }

            SeminarProposal::create([
                'final_project_id' => $p->id,
                'status' => 'APPROVED_BY_SUPERVISORS',
                'proposal_file' => 'seeded-proposal.pdf',
                'student_note' => 'Auto-seeded proposal for testing scheduling',
                'supervisor_one_approval' => 'APPROVED',
                'supervisor_two_approval' => 'APPROVED',
                'submitted_at' => Carbon::now(),
            ]);
        }
    }
}
