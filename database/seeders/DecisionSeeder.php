<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paper;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;

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
            "The paper is accepted with enthusiasm. The reviewers found the research innovative and well-presented.",
            "The paper is accepted for publication. The experimental results are convincing and the methodology is well-designed.",
            "Based on the unanimous positive feedback from reviewers, the paper is accepted. Strong technical contribution.",
            "The paper is accepted with minor editorial changes. The research quality meets the conference standards.",
            "The paper is accepted based on its novel approach and comprehensive experimental validation.",
            "The paper is accepted for its significant contribution to the state-of-the-art in the field."
        ];

        $revisionNotes = [
            "The paper requires major revisions to address the reviewers' concerns. Please pay special attention to the methodology section and experimental validation.",
            "The paper needs significant improvements in presentation and clarity. The core ideas are promising but need better articulation.",
            "Major revisions are required to strengthen the paper's contributions and address the technical concerns raised by reviewers.",
            "The paper shows potential but needs substantial improvements in experimental design and result analysis.",
            "Revision is required to better justify the approach and provide more comprehensive evaluation.",
            "The paper requires revisions to address the methodological concerns raised by the reviewers.",
            "Significant improvements are needed in the experimental section and result presentation.",
            "The paper needs to strengthen its contribution and provide more detailed analysis of the results.",
            "Revision is required to improve the clarity and technical depth of the paper.",
            "The paper needs to address the reviewers' concerns about the experimental validation and comparison."
        ];

        $rejectNotes = [
            "After careful consideration of the reviews, the paper is rejected. The reviewers identified significant issues with the methodology and contributions.",
            "The paper is rejected due to insufficient novelty and technical depth. The experimental results do not adequately support the claims.",
            "Based on the reviewers' feedback, the paper is rejected. The research lacks proper validation and comparison with existing approaches.",
            "The paper is rejected as it does not meet the conference's quality standards. The contributions are not sufficiently novel or significant.",
            "The paper is rejected due to fundamental flaws in the approach and insufficient experimental validation.",
            "The paper is rejected based on the unanimous negative feedback from reviewers regarding technical depth.",
            "The paper is rejected due to lack of sufficient experimental validation and unclear contributions.",
            "The paper is rejected as it does not provide adequate evidence to support its claims.",
            "The paper is rejected due to methodological weaknesses and insufficient technical contribution.",
            "The paper is rejected based on the reviewers' assessment that it does not meet the conference quality standards."
        ];

        foreach ($papers as $paper) {
            // Get completed reviews for this paper
            $completedReviews = $paper->reviews()
                ->where('status', Review::STATUS_COMPLETED)
                ->get();

            // Skip if not enough completed reviews
            if ($completedReviews->count() < config('conference.min_reviews_for_decision', 2)) {
                continue;
            }

            // Calculate average score
            $avgScore = $completedReviews->avg('score');
            
            // Get recommendations
            $recommendations = $completedReviews->pluck('recommendation');
            
            // Make decision based on scores and recommendations with realistic acceptance rates
            $decision = $this->makeRealisticDecision($avgScore, $recommendations, $completedReviews->count());
            
            if ($decision['status'] === 'accept') {
                $status = Paper::STATUS_ACCEPTED;
                $notes = $acceptNotes[array_rand($acceptNotes)];
                $cameraReadyDeadline = $paper->conference->end_date->subDays(30);
            } elseif ($decision['status'] === 'revision') {
                $status = Paper::STATUS_REVISION_REQUIRED;
                $notes = $revisionNotes[array_rand($revisionNotes)];
                $cameraReadyDeadline = null;
            } else {
                $status = Paper::STATUS_REJECTED;
                $notes = $rejectNotes[array_rand($rejectNotes)];
                $cameraReadyDeadline = null;
            }

            // Create varied decision dates
            $decisionDate = now()->subDays(rand(1, 30));

            // Update paper with decision
            $paper->update([
                'status' => $status,
                'decision_notes' => $notes,
                'decision_made_at' => $decisionDate,
                'decision_made_by' => $admins->random()->id,
                'camera_ready_deadline' => $cameraReadyDeadline
            ]);
        }
    }

    /**
     * Make realistic decision based on scores and recommendations
     */
    private function makeRealisticDecision($avgScore, $recommendations, $reviewCount)
    {
        // Calculate acceptance probability based on average score
        $acceptanceProbability = 0;
        
        if ($avgScore >= 8.5) {
            $acceptanceProbability = 85; // 85% chance of acceptance for high scores
        } elseif ($avgScore >= 7.5) {
            $acceptanceProbability = 65; // 65% chance of acceptance for good scores
        } elseif ($avgScore >= 6.5) {
            $acceptanceProbability = 40; // 40% chance of acceptance for medium scores
        } elseif ($avgScore >= 5.5) {
            $acceptanceProbability = 20; // 20% chance of acceptance for low-medium scores
        } else {
            $acceptanceProbability = 5; // 5% chance of acceptance for low scores
        }

        // Adjust based on reviewer recommendations
        $acceptCount = $recommendations->filter(function($rec) {
            return $rec === Review::RECOMMEND_ACCEPT;
        })->count();
        
        $rejectCount = $recommendations->filter(function($rec) {
            return $rec === Review::RECOMMEND_REJECT;
        })->count();

        // If majority recommends accept, increase probability
        if ($acceptCount > ($reviewCount / 2)) {
            $acceptanceProbability += 15;
        }
        
        // If majority recommends reject, decrease probability
        if ($rejectCount > ($reviewCount / 2)) {
            $acceptanceProbability -= 25;
        }

        // Ensure probability is within bounds
        $acceptanceProbability = max(0, min(100, $acceptanceProbability));

        // Make decision
        $rand = rand(1, 100);
        
        if ($rand <= $acceptanceProbability) {
            return ['status' => 'accept'];
        } elseif ($rand <= $acceptanceProbability + 25) {
            return ['status' => 'revision'];
        } else {
            return ['status' => 'reject'];
        }
    }
} 