<?php

namespace App\Policies;

use App\Models\ProgramCommittee;
use App\Models\User;
use App\Models\Conference;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgramCommitteePolicy
{
    use HandlesAuthorization;

    public function managePC(User $user, Conference $conference)
    {
        return $user->role === 'admin' ||
               $conference->programChairs()->where('user_id', $user->id)->exists();
    }

    public function respond(User $user, ProgramCommittee $member)
    {
        return $user->id === $member->user_id;
    }
}
