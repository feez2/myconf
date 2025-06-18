<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proceedings;
use App\Models\Conference;
use App\Models\Paper;

class ProceedingsSeeder extends Seeder
{
    public function run()
    {
        // Get all conferences
        $conferences = Conference::all();
        
        foreach ($conferences as $conference) {
            // Create proceedings for each conference
            $proceedings = Proceedings::create([
                'conference_id' => $conference->id,
                'title' => $conference->title . ' Proceedings',
                'isbn' => '978-' . rand(100000, 999999) . '-' . rand(100, 999) . '-' . rand(0, 9),
                'issn' => rand(1000, 9999) . '-' . rand(1000, 9999),
                'publisher' => 'MYCONF Publications',
                'publication_date' => $conference->end_date,
                'status' => Proceedings::STATUS_DRAFT
            ]);
            
            // Get accepted papers for this conference
            $acceptedPapers = $conference->papers()
                ->where('status', Paper::STATUS_ACCEPTED)
                ->get();
            
            // Assign accepted papers to proceedings
            foreach ($acceptedPapers as $paper) {
                $paper->update([
                    'proceedings_id' => $proceedings->id,
                    'camera_ready_deadline' => $conference->end_date->subDays(30)
                ]);
            }
            
            // If there are accepted papers, update proceedings status
            if ($acceptedPapers->count() > 0) {
                $proceedings->update([
                    'status' => Proceedings::STATUS_PUBLISHED
                ]);
            }
        }
    }
} 