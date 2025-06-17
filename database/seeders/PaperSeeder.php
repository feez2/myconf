<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paper;
use App\Models\User;
use App\Models\Conference;

class PaperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get specific authors and conferences
        $sarah = User::where('email', 'sarah.j@myconf.com')->first();
        $michael = User::where('email', 'michael.c@myconf.com')->first();
        $emma = User::where('email', 'emma.w@myconf.com')->first();
        $david = User::where('email', 'david.k@myconf.com')->first();
        $sophie = User::where('email', 'sophie.m@myconf.com')->first();

        $icai = Conference::where('acronym', 'ICAI')->first();
        $esml = Conference::where('acronym', 'ESML')->first();
        $apcv = Conference::where('acronym', 'APCV')->first();
        $iwnlp = Conference::where('acronym', 'IWNLP')->first();
        $gcds = Conference::where('acronym', 'GCDS')->first();

        // Sarah Johnson's papers
        Paper::create([
            'user_id' => $sarah->id,
            'conference_id' => $icai->id,
            'title' => 'Advanced Machine Learning Techniques for Image Recognition',
            'abstract' => 'This paper presents a comprehensive study on advanced machine learning techniques for image recognition. The research explores various aspects and implications of the topic, providing valuable insights for the academic community.',
            'keywords' => 'machine learning, image recognition, computer vision, deep learning',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);

        Paper::create([
            'user_id' => $sarah->id,
            'conference_id' => $esml->id,
            'title' => 'Natural Language Processing in Healthcare Applications',
            'abstract' => 'This paper explores the application of natural language processing techniques in healthcare, focusing on medical text analysis and patient care improvement.',
            'keywords' => 'NLP, healthcare, medical text, machine learning',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);

        // Michael Chen's papers
        Paper::create([
            'user_id' => $michael->id,
            'conference_id' => $apcv->id,
            'title' => 'Deep Learning Approaches for Autonomous Systems',
            'abstract' => 'This research investigates deep learning methodologies for autonomous systems, with a focus on real-time decision making and environmental adaptation.',
            'keywords' => 'deep learning, autonomous systems, computer vision, robotics',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);

        Paper::create([
            'user_id' => $michael->id,
            'conference_id' => $icai->id,
            'title' => 'Blockchain Technology in Supply Chain Management',
            'abstract' => 'This paper examines the implementation of blockchain technology in supply chain management, highlighting its benefits and challenges.',
            'keywords' => 'blockchain, supply chain, distributed systems, security',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);

        // Emma Wilson's papers
        Paper::create([
            'user_id' => $emma->id,
            'conference_id' => $iwnlp->id,
            'title' => 'Quantum Computing Applications in Cryptography',
            'abstract' => 'This study explores the potential applications of quantum computing in modern cryptography and security systems.',
            'keywords' => 'quantum computing, cryptography, security, algorithms',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);

        Paper::create([
            'user_id' => $emma->id,
            'conference_id' => $gcds->id,
            'title' => 'Big Data Analytics for Smart Cities',
            'abstract' => 'This research investigates the role of big data analytics in developing and managing smart city infrastructure.',
            'keywords' => 'big data, smart cities, urban planning, analytics',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);

        // David Kim's papers
        Paper::create([
            'user_id' => $david->id,
            'conference_id' => $icai->id,
            'title' => 'Cybersecurity Challenges in IoT Devices',
            'abstract' => 'This paper addresses the security challenges faced by IoT devices and proposes solutions for enhanced protection.',
            'keywords' => 'cybersecurity, IoT, network security, device protection',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);

        Paper::create([
            'user_id' => $david->id,
            'conference_id' => $esml->id,
            'title' => 'Cloud Computing Architecture for Enterprise Solutions',
            'abstract' => 'This research presents a comprehensive analysis of cloud computing architectures for enterprise-level applications.',
            'keywords' => 'cloud computing, enterprise architecture, distributed systems',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);

        // Sophie Martin's papers
        Paper::create([
            'user_id' => $sophie->id,
            'conference_id' => $gcds->id,
            'title' => 'Robotics and AI in Manufacturing',
            'abstract' => 'This study examines the integration of robotics and artificial intelligence in modern manufacturing processes.',
            'keywords' => 'robotics, AI, manufacturing, automation',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);

        Paper::create([
            'user_id' => $sophie->id,
            'conference_id' => $apcv->id,
            'title' => 'Data Privacy in Social Media Platforms',
            'abstract' => 'This paper investigates data privacy concerns in social media platforms and proposes solutions for better user protection.',
            'keywords' => 'data privacy, social media, user protection, security',
            'file_path' => 'papers/sample.pdf',
            'status' => 'submitted'
        ]);
    }
} 