<?php

namespace App\Policies;

use App\Models\Proceedings;
use App\Models\User;
use App\Models\Conference;
use App\Models\Paper;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class ProceedingsPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Proceedings $proceedings)
    {
        if ($user->role === 'admin') {
            return true;
        }
        return $user->can('view', $proceedings->conference) ||
               $proceedings->papers()->where('user_id', $user->id)->exists();
    }

    public function manageProceedings(User $user, $conference = null)
    {
        // Admin can manage proceedings for any conference
        if ($user->role === 'admin') {
            Log::info('Admin access granted to proceedings', [
                'user_id' => $user->id,
                'conference_id' => $conference ? $conference->id : null
            ]);
            return true;
        }

        // For non-admin users, require a conference instance
        if (!$conference || $conference === Conference::class) {
            Log::info('Conference instance required for non-admin', [
                'user_id' => $user->id,
                'conference' => $conference
            ]);
            return false;
        }

        return $user->isProgramChair($conference) ||
               $user->isAreaChair($conference);
    }

    public function submitCameraReady(User $user, Paper $paper)
    {
        if ($user->role === 'admin') {
            return true;
        }
        return $user->id === $paper->user_id &&
               $paper->status === Paper::STATUS_ACCEPTED &&
               is_null($paper->camera_ready_file);
    }
}
