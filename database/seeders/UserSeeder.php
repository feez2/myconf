<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'affiliation' => 'MYCONF Organization',
            'country' => 'United States',
            'bio' => 'System Administrator'
        ]);

        // Create more authors for better chart data
        $authors = [
            ['name' => 'Sarah Johnson', 'email' => 'sarah.j@myconf.com', 'affiliation' => 'MIT', 'country' => 'United States', 'bio' => 'AI Researcher specializing in Machine Learning'],
            ['name' => 'Michael Chen', 'email' => 'michael.c@myconf.com', 'affiliation' => 'Stanford University', 'country' => 'United States', 'bio' => 'Computer Vision Expert'],
            ['name' => 'Emma Wilson', 'email' => 'emma.w@myconf.com', 'affiliation' => 'Oxford University', 'country' => 'United Kingdom', 'bio' => 'Natural Language Processing Researcher'],
            ['name' => 'David Kim', 'email' => 'david.k@myconf.com', 'affiliation' => 'Seoul National University', 'country' => 'South Korea', 'bio' => 'Robotics and AI Specialist'],
            ['name' => 'Sophie Martin', 'email' => 'sophie.m@myconf.com', 'affiliation' => 'ETH Zurich', 'country' => 'Switzerland', 'bio' => 'Quantum Computing Researcher'],
            ['name' => 'James Anderson', 'email' => 'james.a@myconf.com', 'affiliation' => 'University of Toronto', 'country' => 'Canada', 'bio' => 'Data Science and Big Data Expert'],
            ['name' => 'Maria Garcia', 'email' => 'maria.g@myconf.com', 'affiliation' => 'Technical University of Madrid', 'country' => 'Spain', 'bio' => 'Cybersecurity Researcher'],
            ['name' => 'Alex Wong', 'email' => 'alex.w@myconf.com', 'affiliation' => 'National University of Singapore', 'country' => 'Singapore', 'bio' => 'Distributed Systems Expert'],
            ['name' => 'Lisa Schmidt', 'email' => 'lisa.s@myconf.com', 'affiliation' => 'Technical University of Berlin', 'country' => 'Germany', 'bio' => 'Software Engineering Researcher'],
            ['name' => 'Raj Patel', 'email' => 'raj.p@myconf.com', 'affiliation' => 'IIT Bombay', 'country' => 'India', 'bio' => 'Machine Learning and AI Researcher'],
            ['name' => 'Yuki Tanaka', 'email' => 'yuki.t@myconf.com', 'affiliation' => 'University of Tokyo', 'country' => 'Japan', 'bio' => 'Computer Vision and Robotics Expert'],
            ['name' => 'Isabella Rossi', 'email' => 'isabella.r@myconf.com', 'affiliation' => 'Politecnico di Milano', 'country' => 'Italy', 'bio' => 'Software Engineering and AI Researcher'],
            ['name' => 'William Clark', 'email' => 'william.c@myconf.com', 'affiliation' => 'Imperial College London', 'country' => 'United Kingdom', 'bio' => 'Data Science and Machine Learning Professor'],
            ['name' => 'Mei Lin', 'email' => 'mei.l@myconf.com', 'affiliation' => 'Tsinghua University', 'country' => 'China', 'bio' => 'AI Research Director and Professor'],
            ['name' => 'Pierre Dubois', 'email' => 'pierre.d@myconf.com', 'affiliation' => 'École Polytechnique', 'country' => 'France', 'bio' => 'Computer Science Professor and Researcher'],
            ['name' => 'Anna Kowalski', 'email' => 'anna.k@myconf.com', 'affiliation' => 'Warsaw University of Technology', 'country' => 'Poland', 'bio' => 'Software Engineering and AI Researcher'],
            ['name' => 'Carlos Rodriguez', 'email' => 'carlos.r@myconf.com', 'affiliation' => 'University of São Paulo', 'country' => 'Brazil', 'bio' => 'Computer Science Professor and AI Researcher'],
            ['name' => 'Fatima Al-Zahra', 'email' => 'fatima.z@myconf.com', 'affiliation' => 'King Abdulaziz University', 'country' => 'Saudi Arabia', 'bio' => 'Machine Learning and Data Mining Expert'],
            ['name' => 'Viktor Petrov', 'email' => 'viktor.p@myconf.com', 'affiliation' => 'Moscow State University', 'country' => 'Russia', 'bio' => 'Computer Vision and Pattern Recognition Researcher'],
            ['name' => 'Hiroshi Yamamoto', 'email' => 'hiroshi.y@myconf.com', 'affiliation' => 'Kyoto University', 'country' => 'Japan', 'bio' => 'Natural Language Processing and AI Researcher'],
        ];

        foreach ($authors as $author) {
            User::create([
                'name' => $author['name'],
                'email' => $author['email'],
                'password' => Hash::make('password'),
                'role' => 'author',
                'affiliation' => $author['affiliation'],
                'country' => $author['country'],
                'bio' => $author['bio']
            ]);
        }

        // Create more reviewers for better review completion data
        $reviewers = [
            ['name' => 'Thomas Brown', 'email' => 'thomas.b@myconf.com', 'affiliation' => 'Harvard University', 'country' => 'United States', 'bio' => 'Senior AI Researcher and Journal Editor'],
            ['name' => 'Patricia Lee', 'email' => 'patricia.l@myconf.com', 'affiliation' => 'University of California, Berkeley', 'country' => 'United States', 'bio' => 'Machine Learning Expert and Program Committee Member'],
            ['name' => 'Hans Mueller', 'email' => 'hans.m@myconf.com', 'affiliation' => 'Max Planck Institute', 'country' => 'Germany', 'bio' => 'Computer Science Professor and Conference Chair'],
            ['name' => 'Yuki Tanaka', 'email' => 'yuki.t.reviewer@myconf.com', 'affiliation' => 'University of Tokyo', 'country' => 'Japan', 'bio' => 'Robotics Professor and Journal Reviewer'],
            ['name' => 'Isabella Rossi', 'email' => 'isabella.r.reviewer@myconf.com', 'affiliation' => 'Politecnico di Milano', 'country' => 'Italy', 'bio' => 'Software Engineering Professor'],
            ['name' => 'William Clark', 'email' => 'william.c.reviewer@myconf.com', 'affiliation' => 'Imperial College London', 'country' => 'United Kingdom', 'bio' => 'Data Science Professor and Program Committee Member'],
            ['name' => 'Mei Lin', 'email' => 'mei.l.reviewer@myconf.com', 'affiliation' => 'Tsinghua University', 'country' => 'China', 'bio' => 'AI Research Director and Journal Editor'],
            ['name' => 'Pierre Dubois', 'email' => 'pierre.d.reviewer@myconf.com', 'affiliation' => 'École Polytechnique', 'country' => 'France', 'bio' => 'Computer Science Professor and Conference Chair'],
            ['name' => 'Anna Kowalski', 'email' => 'anna.k.reviewer@myconf.com', 'affiliation' => 'Warsaw University of Technology', 'country' => 'Poland', 'bio' => 'Software Engineering Professor and Reviewer'],
            ['name' => 'Carlos Rodriguez', 'email' => 'carlos.r.reviewer@myconf.com', 'affiliation' => 'University of São Paulo', 'country' => 'Brazil', 'bio' => 'Computer Science Professor and Program Committee Member'],
            ['name' => 'Jennifer Smith', 'email' => 'jennifer.s@myconf.com', 'affiliation' => 'Carnegie Mellon University', 'country' => 'United States', 'bio' => 'AI and Machine Learning Professor'],
            ['name' => 'Robert Johnson', 'email' => 'robert.j@myconf.com', 'affiliation' => 'University of Michigan', 'country' => 'United States', 'bio' => 'Computer Vision and Robotics Expert'],
            ['name' => 'Linda Davis', 'email' => 'linda.d@myconf.com', 'affiliation' => 'Georgia Institute of Technology', 'country' => 'United States', 'bio' => 'Data Science and Analytics Professor'],
            ['name' => 'Mark Wilson', 'email' => 'mark.w@myconf.com', 'affiliation' => 'University of Illinois', 'country' => 'United States', 'bio' => 'Software Engineering and Systems Professor'],
            ['name' => 'Susan Miller', 'email' => 'susan.m@myconf.com', 'affiliation' => 'University of Washington', 'country' => 'United States', 'bio' => 'Natural Language Processing Expert'],
            ['name' => 'Daniel Taylor', 'email' => 'daniel.t@myconf.com', 'affiliation' => 'University of Texas', 'country' => 'United States', 'bio' => 'Cybersecurity and Network Security Professor'],
            ['name' => 'Amanda White', 'email' => 'amanda.w@myconf.com', 'affiliation' => 'University of Pennsylvania', 'country' => 'United States', 'bio' => 'Machine Learning and AI Researcher'],
            ['name' => 'Kevin Brown', 'email' => 'kevin.b@myconf.com', 'affiliation' => 'University of Wisconsin', 'country' => 'United States', 'bio' => 'Computer Science and Algorithms Professor'],
            ['name' => 'Rachel Green', 'email' => 'rachel.g@myconf.com', 'affiliation' => 'University of Maryland', 'country' => 'United States', 'bio' => 'Information Retrieval and Search Expert'],
            ['name' => 'Steven Black', 'email' => 'steven.b@myconf.com', 'affiliation' => 'University of Virginia', 'country' => 'United States', 'bio' => 'Distributed Systems and Cloud Computing Professor'],
        ];

        foreach ($reviewers as $reviewer) {
            User::create([
                'name' => $reviewer['name'],
                'email' => $reviewer['email'],
                'password' => Hash::make('password'),
                'role' => 'reviewer',
                'affiliation' => $reviewer['affiliation'],
                'country' => $reviewer['country'],
                'bio' => $reviewer['bio']
            ]);
        }
    }
} 