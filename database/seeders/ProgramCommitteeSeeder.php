<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramCommittee;
use App\Models\Conference;
use App\Models\User;

class ProgramCommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all conferences and reviewers
        $conferences = Conference::all();
        $reviewers = User::where('role', 'reviewer')->get();

        // Program committee roles and their distribution
        $roles = ['program_chair', 'area_chair', 'reviewer'];
        $roleWeights = [5, 15, 80]; // Percentages for realistic distribution

        foreach ($conferences as $conference) {
            // Determine number of PC members for this conference
            $pcCount = rand(8, 20); // Varied PC sizes for different conferences
            
            // Select reviewers for this conference
            $conferenceReviewers = $reviewers->random($pcCount);
            
            foreach ($conferenceReviewers as $reviewer) {
                // Select role based on weights
                $roleIndex = $this->weightedRandom($roleWeights);
                $role = $roles[$roleIndex];
                
                // Most PC members should be accepted, some pending
                $status = (rand(1, 100) <= 85) ? 'accepted' : 'pending';
                
                ProgramCommittee::create([
                    'conference_id' => $conference->id,
                    'user_id' => $reviewer->id,
                    'role' => $role,
                    'status' => $status,
                    'invited_at' => now()->subDays(rand(30, 90)),
                    'responded_at' => $status === 'accepted' ? now()->subDays(rand(1, 60)) : null
                ]);
            }
        }
    }

    /**
     * Weighted random selection
     */
    private function weightedRandom($weights)
    {
        $total = array_sum($weights);
        $random = rand(1, $total);
        $current = 0;
        
        foreach ($weights as $index => $weight) {
            $current += $weight;
            if ($random <= $current) {
                return $index;
            }
        }
        
        return 0; // Fallback
    }
} 