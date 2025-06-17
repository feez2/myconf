<?php

namespace App\Policies;

use App\Models\Conference;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConferencePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any conferences.
     */
    public function viewAny(User $user)
    {
        // All users can view conferences
        return true;
    }

    /**
     * Determine whether the user can view the conference.
     */
    public function view(User $user, Conference $conference)
    {
        // All users can view individual conferences
        return true;
    }

    /**
     * Determine whether the user can create conferences.
     */
    public function create(User $user)
    {
        // Only admins can create conferences
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the conference.
     */
    public function update(User $user, Conference $conference)
    {
        // Only admins can update conferences
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the conference.
     */
    public function delete(User $user, Conference $conference)
    {
        // Only admins can delete conferences
        // Also check if conference has no papers to prevent accidental deletion
        return $user->role === 'admin' && $conference->papers()->count() === 0;
    }

    /**
     * Determine whether the user can restore the conference.
     */
    public function restore(User $user, Conference $conference)
    {
        // Only admins can restore conferences
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the conference.
     */
    public function forceDelete(User $user, Conference $conference)
    {
        // Only super admins can force delete
        return $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can manage conference settings.
     */
    public function manageSettings(User $user, Conference $conference)
    {
        // Conference chairs can manage settings
        return $user->role === 'admin' ||
               $conference->chairs()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can assign reviewers.
     */
    public function assignReviewers(User $user, Conference $conference)
    {
        // Admins and program chairs can assign reviewers
        return $user->role === 'admin' ||
               $conference->programChairs()->where('user_id', $user->id)->exists();
    }

    public function viewSubmissions(User $user, Conference $conference)
    {
        // Allow admin access
        if ($user->role === 'admin') {
            return true;
        }

        // Allow conference chairs and program chairs
        return $conference->chairs()->where('user_id', $user->id)->exists() ||
               $conference->programChairs()->where('user_id', $user->id)->exists();
    }

    public function inviteReviewers(User $user, Conference $conference)
    {
        // Debug log to check user role
        \Log::info('User role check:', ['user_id' => $user->id, 'role' => $user->role]);
        
        // Allow admin access - check both string comparison and role attribute
        if ($user->role === 'admin' || $user->isAdmin()) {
            return true;
        }

        // Allow conference chairs and program chairs
        return $conference->chairs()->where('user_id', $user->id)->exists() ||
               $conference->programChairs()->where('user_id', $user->id)->exists();
    }
}
