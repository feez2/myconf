<?php

namespace App\Policies;

use App\Models\Paper;
use App\Models\User;
use App\Models\Conference;
use Illuminate\Auth\Access\HandlesAuthorization;

class DecisionPolicy
{
    use HandlesAuthorization;

    public function makeDecisions(User $user, Conference $conference)
    {
        return $user->isProgramChair($conference) ||
               $user->isAreaChair($conference) ||
               $user->role === 'admin';
    }

    public function viewDecisions(User $user, ?Conference $conference = null)
    {
        // Admin can view decisions for any conference
        if ($user->role === 'admin') {
            return true;
        }

        // For non-admin users, require a conference
        if (!$conference) {
            return false;
        }

        return $this->makeDecisions($user, $conference) ||
               $conference->programCommittees()
                   ->where('user_id', $user->id)
                   ->where('status', 'accepted')
                   ->exists();
    }
}
