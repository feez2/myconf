<?php

namespace App\Listeners;

use App\Events\ReviewAssignedEvent;
use App\Models\User;
use App\Notifications\ReviewAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendReviewAssignedNotification
{
    public function handle(ReviewAssignedEvent $event)
    {
        // Notify conference chairs
        $chairs = $event->review->conference->programChairs;

        foreach ($chairs as $chair) {
            $chair->notify(new ReviewAssigned($event->review));
        }

        // Notify admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new ReviewAssigned($event->review));
        }
    }
}
