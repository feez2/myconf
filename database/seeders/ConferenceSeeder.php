<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conference;

class ConferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conference::create([
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
        ]);

        Conference::create([
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
        ]);

        Conference::create([
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
        ]);

        Conference::create([
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
        ]);

        Conference::create([
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
        ]);
    }
} 