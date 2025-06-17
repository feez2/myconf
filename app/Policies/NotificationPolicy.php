<?php

namespace App\Policies;

use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;

class NotificationPolicy
{
    public function view(User $user, DatabaseNotification $notification)
    {
        return $user->id === $notification->notifiable_id;
    }

    public function update(User $user, DatabaseNotification $notification)
    {
        return $user->id === $notification->notifiable_id;
    }

    public function delete(User $user, DatabaseNotification $notification)
    {
        return $user->id === $notification->notifiable_id;
    }
}
