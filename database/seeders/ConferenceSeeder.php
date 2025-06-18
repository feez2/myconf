<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conference;
use Carbon\Carbon;

class ConferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create conferences with varied dates and submission counts for better chart data
        $conferences = [
            [
                'title' => 'International Conference on Artificial Intelligence',
                'acronym' => 'ICAI',
                'description' => 'A premier conference bringing together researchers and practitioners in artificial intelligence from around the world.',
                'location' => 'San Francisco, USA',
                'start_date' => now()->addMonths(3)->format('Y-m-d'),
                'end_date' => now()->addMonths(3)->addDays(3)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(1)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(2)->format('Y-m-d'),
                'website' => 'https://icai2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'European Symposium on Machine Learning',
                'acronym' => 'ESML',
                'description' => 'Leading European conference on machine learning and its applications.',
                'location' => 'Berlin, Germany',
                'start_date' => now()->addMonths(4)->format('Y-m-d'),
                'end_date' => now()->addMonths(4)->addDays(3)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(2)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(3)->format('Y-m-d'),
                'website' => 'https://esml2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'Asia-Pacific Conference on Computer Vision',
                'acronym' => 'APCV',
                'description' => 'Premier conference on computer vision and pattern recognition in the Asia-Pacific region.',
                'location' => 'Singapore',
                'start_date' => now()->addMonths(5)->format('Y-m-d'),
                'end_date' => now()->addMonths(5)->addDays(3)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(3)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(4)->format('Y-m-d'),
                'website' => 'https://apcv2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'International Workshop on Natural Language Processing',
                'acronym' => 'IWNLP',
                'description' => 'Focused workshop on advances in natural language processing and computational linguistics.',
                'location' => 'London, UK',
                'start_date' => now()->addMonths(6)->format('Y-m-d'),
                'end_date' => now()->addMonths(6)->addDays(2)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(4)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(5)->format('Y-m-d'),
                'website' => 'https://iwnlp2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'Global Conference on Data Science',
                'acronym' => 'GCDS',
                'description' => 'International conference focusing on data science, big data, and analytics.',
                'location' => 'Tokyo, Japan',
                'start_date' => now()->addMonths(7)->format('Y-m-d'),
                'end_date' => now()->addMonths(7)->addDays(3)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(5)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(6)->format('Y-m-d'),
                'website' => 'https://gcds2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'International Conference on Robotics and Automation',
                'acronym' => 'ICRA',
                'description' => 'Leading conference on robotics and automation technologies.',
                'location' => 'Paris, France',
                'start_date' => now()->addMonths(8)->format('Y-m-d'),
                'end_date' => now()->addMonths(8)->addDays(4)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(6)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(7)->format('Y-m-d'),
                'website' => 'https://icra2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'Conference on Computer Vision and Pattern Recognition',
                'acronym' => 'CVPR',
                'description' => 'Premier conference on computer vision and pattern recognition.',
                'location' => 'Seattle, USA',
                'start_date' => now()->addMonths(9)->format('Y-m-d'),
                'end_date' => now()->addMonths(9)->addDays(3)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(7)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(8)->format('Y-m-d'),
                'website' => 'https://cvpr2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'International Conference on Machine Learning',
                'acronym' => 'ICML',
                'description' => 'Leading conference on machine learning research and applications.',
                'location' => 'Vienna, Austria',
                'start_date' => now()->addMonths(10)->format('Y-m-d'),
                'end_date' => now()->addMonths(10)->addDays(4)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(8)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(9)->format('Y-m-d'),
                'website' => 'https://icml2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'Conference on Neural Information Processing Systems',
                'acronym' => 'NeurIPS',
                'description' => 'Premier conference on neural information processing systems.',
                'location' => 'Montreal, Canada',
                'start_date' => now()->addMonths(11)->format('Y-m-d'),
                'end_date' => now()->addMonths(11)->addDays(4)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(9)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(10)->format('Y-m-d'),
                'website' => 'https://neurips2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'International Conference on Software Engineering',
                'acronym' => 'ICSE',
                'description' => 'Leading conference on software engineering research and practice.',
                'location' => 'Melbourne, Australia',
                'start_date' => now()->addMonths(12)->format('Y-m-d'),
                'end_date' => now()->addMonths(12)->addDays(3)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(10)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(11)->format('Y-m-d'),
                'website' => 'https://icse2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'Conference on Human Factors in Computing Systems',
                'acronym' => 'CHI',
                'description' => 'Premier conference on human-computer interaction.',
                'location' => 'Hawaii, USA',
                'start_date' => now()->addMonths(13)->format('Y-m-d'),
                'end_date' => now()->addMonths(13)->addDays(4)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(11)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(12)->format('Y-m-d'),
                'website' => 'https://chi2024.example.com',
                'status' => 'upcoming'
            ],
            [
                'title' => 'International Conference on Distributed Computing Systems',
                'acronym' => 'ICDCS',
                'description' => 'Leading conference on distributed computing and systems.',
                'location' => 'Barcelona, Spain',
                'start_date' => now()->addMonths(14)->format('Y-m-d'),
                'end_date' => now()->addMonths(14)->addDays(3)->format('Y-m-d'),
                'submission_deadline' => now()->addMonths(12)->format('Y-m-d'),
                'review_deadline' => now()->addMonths(13)->format('Y-m-d'),
                'website' => 'https://icdcs2024.example.com',
                'status' => 'upcoming'
            ]
        ];

        foreach ($conferences as $conference) {
            Conference::create($conference);
        }
    }
} 