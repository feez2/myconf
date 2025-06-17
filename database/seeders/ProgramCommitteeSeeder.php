<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conference;
use App\Models\ProgramCommittee;

class ProgramCommitteeSeeder extends Seeder
{
    public function run()
    {
        // Get all reviewers
        $reviewers = User::where('role', 'reviewer')->get();
        
        // Get all conferences
        $conferences = Conference::all();
        
        // Assign each reviewer to one random conference
        foreach ($reviewers as $index => $reviewer) {
            // Get a conference for this reviewer (cycling through conferences if needed)
            $conference = $conferences[$index % $conferences->count()];
            
            // Check if PC member record already exists
            if (!ProgramCommittee::where('conference_id', $conference->id)
                ->where('user_id', $reviewer->id)
                ->exists()) {
                
                ProgramCommittee::create([
                    'conference_id' => $conference->id,
                    'user_id' => $reviewer->id,
                    'status' => 'accepted',
                    'responded_at' => now(),
                ]);
            }
        }
    }
} 