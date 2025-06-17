<?php

namespace App\Listeners;

use App\Events\PaperSubmittedEvent;
use App\Models\User;
use App\Notifications\PaperSubmitted;

class SendPaperSubmittedNotification
{
    public function handle(PaperSubmittedEvent $event)
    {
        // Notify conference chairs
        $chairs = $event->paper->conference->programChairs;

        foreach ($chairs as $chair) {
            $chair->notify(new PaperSubmitted($event->paper));
        }

        // Notify admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new PaperSubmitted($event->paper));
        }
    }
}
