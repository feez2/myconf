<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Paper;
use App\Models\User;
use Carbon\Carbon;

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
            "The paper addresses an important problem and provides a comprehensive solution.",
            "The experimental design is rigorous and the results are well-analyzed. Strong contribution to the field.",
            "The paper demonstrates excellent technical depth and novel insights. Well-written and well-presented.",
            "The methodology is innovative and the experimental validation is thorough. Excellent work.",
            "The paper makes significant contributions to the state-of-the-art. Highly recommended for acceptance.",
            "The research is well-motivated and the technical approach is sound. Strong experimental results."
        ];
        
        $revisionComments = [
            "The paper has potential but needs some improvements in methodology and presentation.",
            "The research is interesting but requires more experimental validation.",
            "The paper needs to clarify some aspects of the proposed approach.",
            "The results are promising but the paper needs better organization and more detailed analysis.",
            "The contribution is valuable but the paper needs to address reviewer concerns.",
            "The paper shows promise but requires significant revisions to improve clarity and technical depth.",
            "The experimental results need more comprehensive analysis and comparison with baseline methods.",
            "The methodology section needs more detailed explanation and justification.",
            "The paper would benefit from additional experiments to validate the proposed approach.",
            "The contribution is clear but the presentation needs improvement for better readability."
        ];
        
        $negativeComments = [
            "The paper lacks novelty and technical depth.",
            "The experimental results are insufficient to support the claims.",
            "The methodology has significant flaws that need to be addressed.",
            "The paper does not make a clear contribution to the field.",
            "The research lacks proper validation and comparison with existing approaches.",
            "The experimental design is flawed and the results are not convincing.",
            "The paper lacks sufficient technical depth and novel contributions.",
            "The methodology is not well-justified and the experimental validation is weak.",
            "The paper does not provide sufficient evidence to support its claims.",
            "The research contribution is minimal and the experimental results are inadequate."
        ];
        
        // Assign reviews to papers with varied completion rates
        foreach ($papers as $paper) {
            // Assign 3 reviewers to each paper
            $paperReviewers = $reviewers->random(3);
            
            foreach ($paperReviewers as $reviewer) {
                // Create varied completion rates for better chart data
                $completionRate = rand(60, 95); // 60-95% completion rate
                $isCompleted = (rand(1, 100) <= $completionRate);
                
                if ($isCompleted) {
                    // Generate a score between 1 and 10 with realistic distribution
                    $score = $this->generateRealisticScore();
                    
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
                    
                    // Create varied completion dates
                    $completionDate = now()->subDays(rand(1, 60));
                    
                    // Create completed review
                    Review::create([
                        'paper_id' => $paper->id,
                        'reviewer_id' => $reviewer->id,
                        'score' => $score,
                        'comments' => $comments,
                        'status' => Review::STATUS_COMPLETED,
                        'recommendation' => $recommendation,
                        'confidential_comments' => "Confidential comments for the program committee regarding the paper's contribution and technical merit.",
                        'completed_at' => $completionDate,
                        'created_at' => $completionDate->subDays(rand(7, 30)),
                        'updated_at' => $completionDate
                    ]);
                } else {
                    // Create pending review
                    $createdDate = now()->subDays(rand(1, 45));
                    Review::create([
                        'paper_id' => $paper->id,
                        'reviewer_id' => $reviewer->id,
                        'status' => Review::STATUS_PENDING,
                        'recommendation' => Review::RECOMMEND_PENDING,
                        'created_at' => $createdDate,
                        'updated_at' => $createdDate
                    ]);
                }
            }
        }
    }

    /**
     * Generate realistic review scores with normal distribution
     */
    private function generateRealisticScore()
    {
        // Generate scores with a more realistic distribution
        // Most scores should be in the 5-8 range, with fewer extreme scores
        $rand = rand(1, 100);
        
        if ($rand <= 5) {
            return rand(1, 3); // 5% very low scores
        } elseif ($rand <= 15) {
            return rand(4, 5); // 10% low scores
        } elseif ($rand <= 45) {
            return rand(6, 7); // 30% medium scores
        } elseif ($rand <= 75) {
            return rand(7, 8); // 30% good scores
        } elseif ($rand <= 90) {
            return rand(8, 9); // 15% very good scores
        } else {
            return 10; // 10% excellent scores
        }
    }
} 