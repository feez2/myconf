<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paper;
use App\Models\User;
use App\Models\Review;

class DecisionSeeder extends Seeder
{
    public function run()
    {
        // Get all papers that have completed reviews
        $papers = Paper::whereHas('reviews', function ($query) {
            $query->where('status', Review::STATUS_COMPLETED);
        })->get();

        // Get admin users who can make decisions
        $admins = User::where('role', 'admin')->get();
        
        // Decision notes templates
        $acceptNotes = [
            "Based on the positive reviews and high scores, the paper is accepted for publication. The reviewers found the methodology sound and the contributions significant.",
            "The paper is accepted with minor revisions. The reviewers were generally positive about the work and its contributions to the field.",
            "After careful consideration of the reviews, the paper is accepted. The research presents novel ideas and is well-executed.",
            "The paper is accepted based on strong reviewer recommendations and high scores. The work makes a valuable contribution to the field.",
            "The paper is accepted with enthusiasm. The reviewers found the research innovative and well-presented."
        ];

        $revisionNotes = [
            "The paper requires major revisions to address the reviewers' concerns. Please pay special attention to the methodology section and experimental validation.",
            "The paper needs significant improvements in presentation and clarity. The core ideas are promising but need better articulation.",
            "Major revisions are required to strengthen the paper's contributions and address the technical concerns raised by reviewers.",
            "The paper shows potential but needs substantial improvements in experimental design and result analysis.",
            "Revision is required to better justify the approach and provide more comprehensive evaluation."
        ];

        $rejectNotes = [
            "After careful consideration of the reviews, the paper is rejected. The reviewers identified significant issues with the methodology and contributions.",
            "The paper is rejected due to insufficient novelty and technical depth. The experimental results do not adequately support the claims.",
            "Based on the reviewers' feedback, the paper is rejected. The research lacks proper validation and comparison with existing approaches.",
            "The paper is rejected as it does not meet the conference's quality standards. The contributions are not sufficiently novel or significant.",
            "The paper is rejected due to fundamental flaws in the approach and insufficient experimental validation."
        ];

        foreach ($papers as $paper) {
            // Get completed reviews for this paper
            $completedReviews = $paper->reviews()
                ->where('status', Review::STATUS_COMPLETED)
                ->get();

            // Skip if not enough completed reviews
            if ($completedReviews->count() < config('conference.min_reviews_for_decision', 3)) {
                continue;
            }

            // Calculate average score
            $avgScore = $completedReviews->avg('score');
            
            // Get recommendations
            $recommendations = $completedReviews->pluck('recommendation');
            
            // Make decision based on scores and recommendations
            if ($avgScore >= config('conference.acceptance_threshold', 8) && 
                $recommendations->contains(Review::RECOMMEND_ACCEPT)) {
                // Accept the paper
                $decision = 'accept';
                $status = Paper::STATUS_ACCEPTED;
                $notes = $acceptNotes[array_rand($acceptNotes)];
                $cameraReadyDeadline = $paper->conference->end_date->subDays(30);
            } elseif ($avgScore >= config('conference.revision_threshold', 5)) {
                // Check if majority recommends revision
                $revisionCount = $recommendations->filter(function($rec) {
                    return in_array($rec, [
                        Review::RECOMMEND_MINOR_REVISION,
                        Review::RECOMMEND_MAJOR_REVISION
                    ]);
                })->count();

                if ($revisionCount > ($completedReviews->count() / 2)) {
                    // Request revision
                    $decision = 'revision';
                    $status = Paper::STATUS_REVISION_REQUIRED;
                    $notes = $revisionNotes[array_rand($revisionNotes)];
                    $cameraReadyDeadline = null;
                } else {
                    // Reject the paper
                    $decision = 'reject';
                    $status = Paper::STATUS_REJECTED;
                    $notes = $rejectNotes[array_rand($rejectNotes)];
                    $cameraReadyDeadline = null;
                }
            } else {
                // Reject the paper
                $decision = 'reject';
                $status = Paper::STATUS_REJECTED;
                $notes = $rejectNotes[array_rand($rejectNotes)];
                $cameraReadyDeadline = null;
            }

            // Update paper with decision
            $paper->update([
                'status' => $status,
                'decision_notes' => $notes,
                'decision_made_at' => now()->subDays(rand(1, 15)),
                'decision_made_by' => $admins->random()->id,
                'camera_ready_deadline' => $cameraReadyDeadline
            ]);
        }
    }
} 