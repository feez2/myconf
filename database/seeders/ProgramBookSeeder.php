<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramBook;
use App\Models\Session;
use App\Models\Presentation;
use App\Models\Conference;
use App\Models\Paper;
use App\Models\User;
use Carbon\Carbon;

class ProgramBookSeeder extends Seeder
{
    public function run()
    {
        // Get all conferences
        $conferences = Conference::all();
        
        foreach ($conferences as $conference) {
            // Create program book for this conference
            $programBook = ProgramBook::create([
                'conference_id' => $conference->id,
                'title' => $conference->title . ' - Program Book',
                'start_date' => $conference->start_date,
                'end_date' => $conference->end_date,
                'welcome_message' => 'Welcome to ' . $conference->title . '! We are delighted to bring together researchers and practitioners from around the world to share the latest advances in our field.',
                'general_information' => 'This program book contains the complete schedule of presentations, sessions, and events for ' . $conference->title . '. Please refer to this schedule for all conference activities.',
                'cover_image_path' => null
            ]);

            // Create sessions for each day of the conference
            $currentDate = Carbon::parse($conference->start_date);
            $endDate = Carbon::parse($conference->end_date);
            $sessionOrder = 1;

            while ($currentDate <= $endDate) {
                // Create different types of sessions for each day
                $this->createDaySessions($programBook, $currentDate, $sessionOrder);
                $currentDate->addDay();
            }
        }
    }

    private function createDaySessions($programBook, $date, &$sessionOrder)
    {
        // Session types and their distribution
        $sessionTypes = [
            'keynote' => ['count' => 1, 'duration' => 60, 'time' => '09:00'],
            'regular' => ['count' => 3, 'duration' => 90, 'time' => '10:30'],
            'workshop' => ['count' => 1, 'duration' => 120, 'time' => '14:00'],
            'poster' => ['count' => 1, 'duration' => 60, 'time' => '16:30']
        ];

        $currentTime = Carbon::parse($date->format('Y-m-d') . ' 08:00');

        foreach ($sessionTypes as $type => $config) {
            for ($i = 0; $i < $config['count']; $i++) {
                $sessionTitle = $this->generateSessionTitle($type, $i + 1);
                $sessionDescription = $this->generateSessionDescription($type);
                
                // Calculate session times
                $startTime = $currentTime->copy();
                $endTime = $startTime->copy()->addMinutes($config['duration']);
                
                // Create session
                $session = Session::create([
                    'program_book_id' => $programBook->id,
                    'title' => $sessionTitle,
                    'description' => $sessionDescription,
                    'date' => $date,
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $endTime->format('H:i'),
                    'location' => $this->generateLocation($type),
                    'session_chair' => $this->getRandomSessionChair(),
                    'order' => $sessionOrder++,
                    'type' => $type
                ]);

                // Create presentations for this session
                $this->createSessionPresentations($session, $startTime, $endTime);

                // Move to next session time
                $currentTime = $endTime->addMinutes(30); // 30-minute break between sessions
            }
        }
    }

    private function createSessionPresentations($session, $sessionStart, $sessionEnd)
    {
        $presentationDuration = 20; // 20 minutes per presentation
        $presentationOrder = 1;
        $currentTime = $sessionStart->copy();

        // Get accepted papers for this conference
        $acceptedPapers = Paper::where('conference_id', $session->programBook->conference_id)
            ->where('status', 'accepted')
            ->get();

        // For keynote sessions, create keynote presentations
        if ($session->type === 'keynote') {
            $this->createKeynotePresentation($session, $currentTime, $presentationOrder++);
            return;
        }

        // For regular sessions, create paper presentations
        if ($session->type === 'regular') {
            $papersForSession = $acceptedPapers->random(min(4, $acceptedPapers->count()));
            
            foreach ($papersForSession as $paper) {
                $presentationEnd = $currentTime->copy()->addMinutes($presentationDuration);
                
                // Ensure presentation doesn't exceed session time
                if ($presentationEnd > $sessionEnd) {
                    break;
                }

                Presentation::create([
                    'session_id' => $session->id,
                    'paper_id' => $paper->id,
                    'title' => $paper->title,
                    'abstract' => $paper->abstract,
                    'start_time' => $currentTime->format('H:i'),
                    'end_time' => $presentationEnd->format('H:i'),
                    'speaker_name' => $paper->user->name,
                    'speaker_affiliation' => $paper->user->affiliation,
                    'speaker_bio' => $paper->user->bio,
                    'speaker_photo_path' => null,
                    'order' => $presentationOrder++
                ]);

                $currentTime = $presentationEnd->addMinutes(5); // 5-minute break between presentations
            }
        }

        // For workshop sessions, create workshop presentations
        if ($session->type === 'workshop') {
            $this->createWorkshopPresentations($session, $currentTime, $presentationOrder);
        }

        // For poster sessions, create poster presentations
        if ($session->type === 'poster') {
            $this->createPosterPresentations($session, $currentTime, $presentationOrder);
        }
    }

    private function createKeynotePresentation($session, $startTime, $order)
    {
        $endTime = $startTime->copy()->addMinutes(60);
        
        $keynoteSpeakers = [
            ['name' => 'Dr. Sarah Johnson', 'affiliation' => 'MIT', 'bio' => 'Leading researcher in artificial intelligence and machine learning'],
            ['name' => 'Prof. Michael Chen', 'affiliation' => 'Stanford University', 'bio' => 'Expert in computer vision and deep learning'],
            ['name' => 'Dr. Emma Wilson', 'affiliation' => 'Oxford University', 'bio' => 'Pioneer in natural language processing'],
            ['name' => 'Prof. David Kim', 'affiliation' => 'Seoul National University', 'bio' => 'Renowned robotics and AI specialist'],
            ['name' => 'Dr. Sophie Martin', 'affiliation' => 'ETH Zurich', 'bio' => 'Leading quantum computing researcher']
        ];

        $speaker = $keynoteSpeakers[array_rand($keynoteSpeakers)];

        Presentation::create([
            'session_id' => $session->id,
            'paper_id' => null,
            'title' => 'Keynote: Future Directions in ' . $this->getConferenceTopic($session->programBook->conference),
            'abstract' => 'This keynote presentation will explore the latest trends and future directions in the field, providing insights into emerging technologies and research opportunities.',
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'speaker_name' => $speaker['name'],
            'speaker_affiliation' => $speaker['affiliation'],
            'speaker_bio' => $speaker['bio'],
            'speaker_photo_path' => null,
            'order' => $order
        ]);
    }

    private function createWorkshopPresentations($session, $startTime, $order)
    {
        $workshopTopics = [
            'Hands-on Machine Learning with TensorFlow',
            'Advanced Deep Learning Techniques',
            'Practical Applications of Computer Vision',
            'Natural Language Processing Workshop',
            'Data Science and Analytics Tools'
        ];

        $topic = $workshopTopics[array_rand($workshopTopics)];
        $endTime = $startTime->copy()->addMinutes(120);

        Presentation::create([
            'session_id' => $session->id,
            'paper_id' => null,
            'title' => $topic,
            'abstract' => 'This interactive workshop will provide hands-on experience with the latest tools and techniques in the field.',
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'speaker_name' => 'Workshop Facilitators',
            'speaker_affiliation' => 'Various Institutions',
            'speaker_bio' => 'Experienced researchers and practitioners in the field',
            'speaker_photo_path' => null,
            'order' => $order
        ]);
    }

    private function createPosterPresentations($session, $startTime, $order)
    {
        $endTime = $startTime->copy()->addMinutes(60);

        Presentation::create([
            'session_id' => $session->id,
            'paper_id' => null,
            'title' => 'Poster Session: Research Highlights',
            'abstract' => 'Interactive poster session featuring the latest research highlights and ongoing projects from conference participants.',
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'speaker_name' => 'Multiple Presenters',
            'speaker_affiliation' => 'Various Institutions',
            'speaker_bio' => 'Conference participants presenting their research',
            'speaker_photo_path' => null,
            'order' => $order
        ]);
    }

    private function generateSessionTitle($type, $number)
    {
        $titles = [
            'keynote' => [
                'Opening Keynote: Future of AI',
                'Plenary Session: Emerging Technologies',
                'Distinguished Lecture: Research Frontiers'
            ],
            'regular' => [
                'Session ' . $number . ': Machine Learning Applications',
                'Session ' . $number . ': Computer Vision and Pattern Recognition',
                'Session ' . $number . ': Natural Language Processing',
                'Session ' . $number . ': Data Science and Analytics',
                'Session ' . $number . ': Software Engineering and Systems'
            ],
            'workshop' => [
                'Hands-on Workshop: Practical Applications',
                'Interactive Workshop: Advanced Techniques',
                'Tutorial Session: Tools and Frameworks'
            ],
            'poster' => [
                'Poster Session: Research Highlights',
                'Interactive Poster Presentations',
                'Research Showcase Session'
            ]
        ];

        return $titles[$type][array_rand($titles[$type])];
    }

    private function generateSessionDescription($type)
    {
        $descriptions = [
            'keynote' => 'This keynote session features distinguished speakers presenting cutting-edge research and future directions in the field.',
            'regular' => 'This session presents peer-reviewed research papers covering various aspects of the conference theme.',
            'workshop' => 'This interactive workshop provides hands-on experience with the latest tools and techniques.',
            'poster' => 'This poster session showcases ongoing research and provides opportunities for interactive discussions.'
        ];

        return $descriptions[$type];
    }

    private function generateLocation($type)
    {
        $locations = [
            'keynote' => 'Main Auditorium',
            'regular' => ['Room A', 'Room B', 'Room C', 'Room D'],
            'workshop' => 'Workshop Room',
            'poster' => 'Exhibition Hall'
        ];

        if ($type === 'regular') {
            return $locations[$type][array_rand($locations[$type])];
        }

        return $locations[$type];
    }

    private function getRandomSessionChair()
    {
        $chairs = [
            'Dr. Thomas Brown - Harvard University',
            'Prof. Patricia Lee - UC Berkeley',
            'Dr. Hans Mueller - Max Planck Institute',
            'Prof. Yuki Tanaka - University of Tokyo',
            'Dr. Isabella Rossi - Politecnico di Milano',
            'Prof. William Clark - Imperial College London'
        ];

        return $chairs[array_rand($chairs)];
    }

    private function getConferenceTopic($conference)
    {
        $topics = [
            'ICAI' => 'Artificial Intelligence',
            'ESML' => 'Machine Learning',
            'APCV' => 'Computer Vision',
            'IWNLP' => 'Natural Language Processing',
            'GCDS' => 'Data Science',
            'ICRA' => 'Robotics and Automation',
            'CVPR' => 'Computer Vision and Pattern Recognition',
            'ICML' => 'Machine Learning',
            'NeurIPS' => 'Neural Information Processing',
            'ICSE' => 'Software Engineering',
            'CHI' => 'Human-Computer Interaction',
            'ICDCS' => 'Distributed Computing'
        ];

        return $topics[$conference->acronym] ?? 'Technology';
    }
} 