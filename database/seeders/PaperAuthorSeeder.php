<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paper;
use App\Models\PaperAuthor;
use App\Models\User;
use Faker\Factory as Faker;

class PaperAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Get all papers and users
        $papers = Paper::all();
        $users = User::where('role', 'author')->get();
        
        // External author names for papers without registered users
        $externalAuthors = [
            ['name' => 'Dr. Elena Rodriguez', 'email' => 'elena.rodriguez@university.edu', 'affiliation' => 'University of Barcelona'],
            ['name' => 'Prof. Ahmed Hassan', 'email' => 'ahmed.hassan@cairo.edu', 'affiliation' => 'Cairo University'],
            ['name' => 'Dr. Wei Zhang', 'email' => 'wei.zhang@beijing.edu', 'affiliation' => 'Beijing Institute of Technology'],
            ['name' => 'Prof. Maria Santos', 'email' => 'maria.santos@portugal.edu', 'affiliation' => 'University of Lisbon'],
            ['name' => 'Dr. Ivan Petrov', 'email' => 'ivan.petrov@moscow.edu', 'affiliation' => 'Moscow State University'],
            ['name' => 'Prof. Kim Jong-Soo', 'email' => 'kim.jongsoo@seoul.edu', 'affiliation' => 'Seoul National University'],
            ['name' => 'Dr. Anna Kowalczyk', 'email' => 'anna.kowalczyk@warsaw.edu', 'affiliation' => 'Warsaw University of Technology'],
            ['name' => 'Prof. Carlos Mendez', 'email' => 'carlos.mendez@mexico.edu', 'affiliation' => 'National Autonomous University of Mexico'],
            ['name' => 'Dr. Fatima Al-Rashid', 'email' => 'fatima.alrashid@riyadh.edu', 'affiliation' => 'King Saud University'],
            ['name' => 'Prof. Hiroshi Tanaka', 'email' => 'hiroshi.tanaka@osaka.edu', 'affiliation' => 'Osaka University'],
            ['name' => 'Dr. Sofia Papadopoulos', 'email' => 'sofia.papadopoulos@athens.edu', 'affiliation' => 'National Technical University of Athens'],
            ['name' => 'Prof. Lars Johansson', 'email' => 'lars.johansson@stockholm.edu', 'affiliation' => 'KTH Royal Institute of Technology'],
            ['name' => 'Dr. Priya Sharma', 'email' => 'priya.sharma@delhi.edu', 'affiliation' => 'Delhi Technological University'],
            ['name' => 'Prof. Roberto Silva', 'email' => 'roberto.silva@saopaulo.edu', 'affiliation' => 'University of São Paulo'],
            ['name' => 'Dr. Yuki Yamamoto', 'email' => 'yuki.yamamoto@kyoto.edu', 'affiliation' => 'Kyoto University'],
            ['name' => 'Prof. Elena Popov', 'email' => 'elena.popov@saintpetersburg.edu', 'affiliation' => 'Saint Petersburg State University'],
            ['name' => 'Dr. Mohammed Al-Zahra', 'email' => 'mohammed.alzahra@dubai.edu', 'affiliation' => 'American University of Dubai'],
            ['name' => 'Prof. Isabella Romano', 'email' => 'isabella.romano@rome.edu', 'affiliation' => 'Sapienza University of Rome'],
            ['name' => 'Dr. Pavel Novak', 'email' => 'pavel.novak@prague.edu', 'affiliation' => 'Czech Technical University'],
            ['name' => 'Prof. Li Wei', 'email' => 'li.wei@shanghai.edu', 'affiliation' => 'Shanghai Jiao Tong University'],
        ];

        foreach ($papers as $paper) {
            // Determine number of authors for this paper (1-4 authors is realistic)
            $authorCount = rand(1, 4);
            
            // Always include the paper's main author (user_id from paper)
            if ($paper->user_id) {
                $mainAuthor = User::find($paper->user_id);
                if ($mainAuthor) {
                    PaperAuthor::create([
                        'paper_id' => $paper->id,
                        'user_id' => $mainAuthor->id,
                        'name' => $mainAuthor->name,
                        'email' => $mainAuthor->email,
                        'affiliation' => $mainAuthor->affiliation,
                        'is_corresponding' => true, // Main author is usually corresponding
                        'order' => 1
                    ]);
                }
            }
            
            // Add additional authors (mix of registered users and external authors)
            for ($i = 2; $i <= $authorCount; $i++) {
                $isRegisteredUser = rand(0, 1); // 50% chance of being a registered user
                
                if ($isRegisteredUser && $users->count() > 0) {
                    // Use a registered user
                    $user = $users->random();
                    PaperAuthor::create([
                        'paper_id' => $paper->id,
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'affiliation' => $user->affiliation,
                        'is_corresponding' => false,
                        'order' => $i
                    ]);
                } else {
                    // Use an external author
                    $externalAuthor = $externalAuthors[array_rand($externalAuthors)];
                    PaperAuthor::create([
                        'paper_id' => $paper->id,
                        'user_id' => null, // External author, no user account
                        'name' => $externalAuthor['name'],
                        'email' => $externalAuthor['email'],
                        'affiliation' => $externalAuthor['affiliation'],
                        'is_corresponding' => false,
                        'order' => $i
                    ]);
                }
            }
            
            // Ensure at least one corresponding author exists
            $correspondingAuthors = PaperAuthor::where('paper_id', $paper->id)
                ->where('is_corresponding', true)
                ->count();
                
            if ($correspondingAuthors === 0) {
                // Make the first author corresponding if none exists
                $firstAuthor = PaperAuthor::where('paper_id', $paper->id)
                    ->orderBy('order')
                    ->first();
                if ($firstAuthor) {
                    $firstAuthor->update(['is_corresponding' => true]);
                }
            }
        }
        
        // Create some papers with only external authors (no registered users)
        $papersWithoutUsers = Paper::whereNull('user_id')->take(10)->get();
        
        foreach ($papersWithoutUsers as $paper) {
            $authorCount = rand(2, 4);
            
            for ($i = 1; $i <= $authorCount; $i++) {
                $externalAuthor = $externalAuthors[array_rand($externalAuthors)];
                PaperAuthor::create([
                    'paper_id' => $paper->id,
                    'user_id' => null,
                    'name' => $externalAuthor['name'],
                    'email' => $externalAuthor['email'],
                    'affiliation' => $externalAuthor['affiliation'],
                    'is_corresponding' => ($i === 1), // First author is corresponding
                    'order' => $i
                ]);
            }
        }
    }
} 