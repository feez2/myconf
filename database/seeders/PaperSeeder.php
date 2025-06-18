<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paper;
use App\Models\User;
use App\Models\Conference;
use Carbon\Carbon;

class PaperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all authors and conferences
        $authors = User::where('role', 'author')->get();
        $conferences = Conference::all();

        // Paper titles and abstracts for variety
        $paperData = [
            // AI and Machine Learning papers
            ['title' => 'Advanced Machine Learning Techniques for Image Recognition', 'keywords' => 'machine learning, image recognition, computer vision, deep learning'],
            ['title' => 'Natural Language Processing in Healthcare Applications', 'keywords' => 'NLP, healthcare, medical text, machine learning'],
            ['title' => 'Deep Learning Approaches for Autonomous Systems', 'keywords' => 'deep learning, autonomous systems, computer vision, robotics'],
            ['title' => 'Blockchain Technology in Supply Chain Management', 'keywords' => 'blockchain, supply chain, distributed systems, security'],
            ['title' => 'Quantum Computing Applications in Cryptography', 'keywords' => 'quantum computing, cryptography, security, algorithms'],
            ['title' => 'Big Data Analytics for Smart Cities', 'keywords' => 'big data, smart cities, urban planning, analytics'],
            ['title' => 'Cybersecurity Challenges in IoT Devices', 'keywords' => 'cybersecurity, IoT, network security, device protection'],
            ['title' => 'Cloud Computing Architecture for Enterprise Solutions', 'keywords' => 'cloud computing, enterprise architecture, distributed systems'],
            ['title' => 'Robotics and AI in Manufacturing', 'keywords' => 'robotics, AI, manufacturing, automation'],
            ['title' => 'Data Privacy in Social Media Platforms', 'keywords' => 'data privacy, social media, user protection, security'],
            
            // Computer Vision papers
            ['title' => 'Real-time Object Detection Using YOLO Architecture', 'keywords' => 'computer vision, object detection, YOLO, real-time'],
            ['title' => 'Facial Recognition Systems: Privacy and Ethics', 'keywords' => 'facial recognition, privacy, ethics, computer vision'],
            ['title' => 'Medical Image Analysis with Deep Learning', 'keywords' => 'medical imaging, deep learning, healthcare, computer vision'],
            ['title' => '3D Scene Understanding from 2D Images', 'keywords' => '3D vision, scene understanding, computer vision, deep learning'],
            ['title' => 'Video Analysis for Surveillance Applications', 'keywords' => 'video analysis, surveillance, computer vision, security'],
            
            // Natural Language Processing papers
            ['title' => 'Transformer Models for Text Classification', 'keywords' => 'NLP, transformers, text classification, deep learning'],
            ['title' => 'Sentiment Analysis in Social Media', 'keywords' => 'sentiment analysis, social media, NLP, machine learning'],
            ['title' => 'Machine Translation Quality Assessment', 'keywords' => 'machine translation, quality assessment, NLP'],
            ['title' => 'Question Answering Systems with BERT', 'keywords' => 'question answering, BERT, NLP, deep learning'],
            ['title' => 'Text Summarization Using Neural Networks', 'keywords' => 'text summarization, neural networks, NLP'],
            
            // Data Science papers
            ['title' => 'Predictive Analytics in Financial Markets', 'keywords' => 'predictive analytics, finance, machine learning, data science'],
            ['title' => 'Customer Segmentation Using Clustering Algorithms', 'keywords' => 'customer segmentation, clustering, data science, marketing'],
            ['title' => 'Anomaly Detection in Network Traffic', 'keywords' => 'anomaly detection, network security, data science'],
            ['title' => 'Recommendation Systems for E-commerce', 'keywords' => 'recommendation systems, e-commerce, machine learning'],
            ['title' => 'Time Series Forecasting with LSTM Networks', 'keywords' => 'time series, LSTM, forecasting, deep learning'],
            
            // Software Engineering papers
            ['title' => 'Microservices Architecture Patterns', 'keywords' => 'microservices, software architecture, distributed systems'],
            ['title' => 'Test-Driven Development in Agile Environments', 'keywords' => 'TDD, agile, software testing, development'],
            ['title' => 'Code Quality Metrics and Analysis', 'keywords' => 'code quality, metrics, software engineering'],
            ['title' => 'Continuous Integration and Deployment', 'keywords' => 'CI/CD, DevOps, software engineering'],
            ['title' => 'Software Refactoring Techniques', 'keywords' => 'refactoring, software engineering, code maintenance'],
            
            // Cybersecurity papers
            ['title' => 'Zero-Day Vulnerability Detection', 'keywords' => 'cybersecurity, zero-day, vulnerability detection'],
            ['title' => 'Cryptographic Protocols for IoT Security', 'keywords' => 'cryptography, IoT, security, protocols'],
            ['title' => 'Malware Analysis Using Machine Learning', 'keywords' => 'malware analysis, machine learning, cybersecurity'],
            ['title' => 'Network Intrusion Detection Systems', 'keywords' => 'intrusion detection, network security, cybersecurity'],
            ['title' => 'Secure Multi-Party Computation', 'keywords' => 'secure computation, cryptography, privacy'],
            
            // Distributed Systems papers
            ['title' => 'Consensus Algorithms in Distributed Systems', 'keywords' => 'consensus, distributed systems, algorithms'],
            ['title' => 'Load Balancing in Cloud Environments', 'keywords' => 'load balancing, cloud computing, distributed systems'],
            ['title' => 'Fault Tolerance in Distributed Databases', 'keywords' => 'fault tolerance, distributed databases, reliability'],
            ['title' => 'Edge Computing for IoT Applications', 'keywords' => 'edge computing, IoT, distributed systems'],
            ['title' => 'Service Mesh Architecture Patterns', 'keywords' => 'service mesh, microservices, distributed systems'],
            
            // Human-Computer Interaction papers
            ['title' => 'User Experience Design for Mobile Applications', 'keywords' => 'UX design, mobile apps, HCI'],
            ['title' => 'Accessibility in Web Applications', 'keywords' => 'accessibility, web applications, HCI'],
            ['title' => 'Gesture Recognition in Virtual Reality', 'keywords' => 'gesture recognition, VR, HCI'],
            ['title' => 'Voice User Interface Design', 'keywords' => 'voice UI, HCI, interface design'],
            ['title' => 'Eye Tracking for User Behavior Analysis', 'keywords' => 'eye tracking, user behavior, HCI'],
        ];

        // Status distribution for realistic data
        $statuses = ['submitted', 'under_review', 'revision_required', 'accepted', 'rejected', 'withdrawn'];
        $statusWeights = [25, 30, 15, 20, 8, 2]; // Percentages for realistic distribution

        // Create papers with varied submission dates and statuses
        foreach ($conferences as $conference) {
            // Determine number of papers for this conference (varied for chart diversity)
            $paperCount = rand(15, 45); // Different submission counts per conference
            
            for ($i = 0; $i < $paperCount; $i++) {
                $paperInfo = $paperData[array_rand($paperData)];
                $author = $authors->random();
                
                // Create varied submission dates (spread over months for timeline chart)
                $submissionDate = $conference->submission_deadline->subDays(rand(1, 180)); // Up to 6 months before deadline
                
                // Select status based on weights
                $statusIndex = $this->weightedRandom($statusWeights);
                $status = $statuses[$statusIndex];
                
                // Create the paper
                $paper = Paper::create([
                    'user_id' => $author->id,
                    'conference_id' => $conference->id,
                    'title' => $paperInfo['title'] . ' - ' . $conference->acronym . ' ' . ($i + 1),
                    'abstract' => 'This paper presents a comprehensive study on ' . strtolower($paperInfo['title']) . '. The research explores various aspects and implications of the topic, providing valuable insights for the academic community. The methodology employed includes both theoretical analysis and practical experimentation, ensuring robust and reliable results.',
                    'keywords' => $paperInfo['keywords'],
                    'file_path' => 'papers/sample.pdf',
                    'status' => $status,
                    'created_at' => $submissionDate,
                    'updated_at' => $submissionDate
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