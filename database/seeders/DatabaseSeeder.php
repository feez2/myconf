<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ConferenceSeeder::class,
            PaperSeeder::class,
            PaperAuthorSeeder::class,
            ProgramCommitteeSeeder::class,
            ReviewSeeder::class,
            DecisionSeeder::class,
            ProceedingsSeeder::class,
            ProgramBookSeeder::class
        ]);
    }
}
