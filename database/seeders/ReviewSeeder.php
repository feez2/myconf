<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Paper;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Get all reviewers
        $reviewers = User::where('role', 'reviewer')->get();
        
        // Get all papers
        $papers = Paper::all();
        
        // Review comments templates
        $positiveComments = [
            "The paper presents a novel approach to the problem. The methodology is sound and well-justified.",
            "The research is well-structured and the results are significant. The paper makes a valuable contribution to the field.",
            "The authors have done an excellent job in presenting their work. The experimental results are convincing.",
            "This is a high-quality paper with clear contributions. The technical depth is appropriate for the conference.",
            "The paper addresses an important problem and provides a comprehensive solution."
        ];
        
        $revisionComments = [
            "The paper has potential but needs some improvements in methodology and presentation.",
            "The research is interesting but requires more experimental validation.",
            "The paper needs to clarify some aspects of the proposed approach.",
            "The results are promising but the paper needs better organization and more detailed analysis.",
            "The contribution is valuable but the paper needs to address reviewer concerns."
        ];
        
        $negativeComments = [
            "The paper lacks novelty and technical depth.",
            "The experimental results are insufficient to support the claims.",
            "The methodology has significant flaws that need to be addressed.",
            "The paper does not make a clear contribution to the field.",
            "The research lacks proper validation and comparison with existing approaches."
        ];
        
        // Assign reviews to papers
        foreach ($papers as $paper) {
            // Assign 3 reviewers to each paper
            $paperReviewers = $reviewers->random(3);
            
            foreach ($paperReviewers as $reviewer) {
                // Randomly decide if the review is completed
                $isCompleted = rand(0, 1);
                
                if ($isCompleted) {
                    // Generate a score between 1 and 10
                    $score = rand(1, 10);
                    
                    // Determine recommendation based on score
                    if ($score >= 8) {
                        $recommendation = Review::RECOMMEND_ACCEPT;
                        $comments = $positiveComments[array_rand($positiveComments)];
                    } elseif ($score >= 5) {
                        $recommendation = rand(0, 1) ? Review::RECOMMEND_MINOR_REVISION : Review::RECOMMEND_MAJOR_REVISION;
                        $comments = $revisionComments[array_rand($revisionComments)];
                    } else {
                        $recommendation = Review::RECOMMEND_REJECT;
                        $comments = $negativeComments[array_rand($negativeComments)];
                    }
                    
                    // Create completed review
                    Review::create([
                        'paper_id' => $paper->id,
                        'reviewer_id' => $reviewer->id,
                        'score' => $score,
                        'comments' => $comments,
                        'status' => Review::STATUS_COMPLETED,
                        'recommendation' => $recommendation,
                        'confidential_comments' => "Confidential comments for the program committee.",
                        'completed_at' => now()->subDays(rand(1, 30))
                    ]);
                } else {
                    // Create pending review
                    Review::create([
                        'paper_id' => $paper->id,
                        'reviewer_id' => $reviewer->id,
                        'status' => Review::STATUS_PENDING,
                        'recommendation' => Review::RECOMMEND_PENDING
                    ]);
                }
            }
        }
    }
} 