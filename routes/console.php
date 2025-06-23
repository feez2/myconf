<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('papers:update-to-under-review', function () {
    \App\Models\Paper::updateSubmittedToUnderReview();
    $this->info('Updated submitted papers to under_review where deadline has passed.');
})->describe('Update all submitted papers to under_review if their conference submission deadline has passed.');
