<?php

namespace App\Listeners;

use App\Events\PaperSubmittedEvent;
use App\Models\User;
use App\Notifications\PaperSubmitted;

class SendPaperSubmittedNotification
{
    public function handle(PaperSubmittedEvent $event)
    {
        // Notify conference chairs (program chairs)
        $programChairs = $event->paper->conference->programChairs;
        foreach ($programChairs as $committee) {
            if ($committee->user) {
                $committee->user->notify(new PaperSubmitted($event->paper));
            }
        }

        // Notify admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new PaperSubmitted($event->paper));
        }
    }
}
