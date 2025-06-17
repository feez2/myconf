<?php

namespace App\Policies;

use App\Models\Conference;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgramBookPolicy
{
    use HandlesAuthorization;

    public function manageProgramBook(User $user, Conference $conference)
    {
        return $user->role === 'admin' ||
               $user->isProgramChair($conference) ||
               $user->isAreaChair($conference);
    }
}
