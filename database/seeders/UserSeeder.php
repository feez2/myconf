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

        // Create authors
        User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.j@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'affiliation' => 'MIT',
            'country' => 'United States',
            'bio' => 'AI Researcher specializing in Machine Learning'
        ]);

        User::create([
            'name' => 'Michael Chen',
            'email' => 'michael.c@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'affiliation' => 'Stanford University',
            'country' => 'United States',
            'bio' => 'Computer Vision Expert'
        ]);

        User::create([
            'name' => 'Emma Wilson',
            'email' => 'emma.w@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'affiliation' => 'Oxford University',
            'country' => 'United Kingdom',
            'bio' => 'Natural Language Processing Researcher'
        ]);

        User::create([
            'name' => 'David Kim',
            'email' => 'david.k@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'affiliation' => 'Seoul National University',
            'country' => 'South Korea',
            'bio' => 'Robotics and AI Specialist'
        ]);

        User::create([
            'name' => 'Sophie Martin',
            'email' => 'sophie.m@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'affiliation' => 'ETH Zurich',
            'country' => 'Switzerland',
            'bio' => 'Quantum Computing Researcher'
        ]);

        User::create([
            'name' => 'James Anderson',
            'email' => 'james.a@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'affiliation' => 'University of Toronto',
            'country' => 'Canada',
            'bio' => 'Data Science and Big Data Expert'
        ]);

        User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria.g@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'affiliation' => 'Technical University of Madrid',
            'country' => 'Spain',
            'bio' => 'Cybersecurity Researcher'
        ]);

        User::create([
            'name' => 'Alex Wong',
            'email' => 'alex.w@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'affiliation' => 'National University of Singapore',
            'country' => 'Singapore',
            'bio' => 'Distributed Systems Expert'
        ]);

        User::create([
            'name' => 'Lisa Schmidt',
            'email' => 'lisa.s@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'affiliation' => 'Technical University of Berlin',
            'country' => 'Germany',
            'bio' => 'Software Engineering Researcher'
        ]);

        // Create reviewers
        User::create([
            'name' => 'Thomas Brown',
            'email' => 'thomas.b@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'Harvard University',
            'country' => 'United States',
            'bio' => 'Senior AI Researcher and Journal Editor'
        ]);

        User::create([
            'name' => 'Patricia Lee',
            'email' => 'patricia.l@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'University of California, Berkeley',
            'country' => 'United States',
            'bio' => 'Machine Learning Expert and Program Committee Member'
        ]);

        User::create([
            'name' => 'Hans Mueller',
            'email' => 'hans.m@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'Max Planck Institute',
            'country' => 'Germany',
            'bio' => 'Computer Science Professor and Conference Chair'
        ]);

        User::create([
            'name' => 'Yuki Tanaka',
            'email' => 'yuki.t@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'University of Tokyo',
            'country' => 'Japan',
            'bio' => 'Robotics Professor and Journal Reviewer'
        ]);

        User::create([
            'name' => 'Isabella Rossi',
            'email' => 'isabella.r@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'Politecnico di Milano',
            'country' => 'Italy',
            'bio' => 'Software Engineering Professor'
        ]);

        User::create([
            'name' => 'William Clark',
            'email' => 'william.c@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'Imperial College London',
            'country' => 'United Kingdom',
            'bio' => 'Data Science Professor and Program Committee Member'
        ]);

        User::create([
            'name' => 'Mei Lin',
            'email' => 'mei.l@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'Tsinghua University',
            'country' => 'China',
            'bio' => 'AI Research Director and Journal Editor'
        ]);

        User::create([
            'name' => 'Pierre Dubois',
            'email' => 'pierre.d@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'École Polytechnique',
            'country' => 'France',
            'bio' => 'Computer Science Professor and Conference Chair'
        ]);

        User::create([
            'name' => 'Anna Kowalski',
            'email' => 'anna.k@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'Warsaw University of Technology',
            'country' => 'Poland',
            'bio' => 'Software Engineering Professor and Reviewer'
        ]);

        User::create([
            'name' => 'Carlos Rodriguez',
            'email' => 'carlos.r@myconf.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'affiliation' => 'University of São Paulo',
            'country' => 'Brazil',
            'bio' => 'Computer Science Professor and Program Committee Member'
        ]);
    }
} 