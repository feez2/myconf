<?php

namespace App\Policies;

use App\Models\Paper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaperPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Paper $paper)
    {
        // Author can view
        if ($user->id === $paper->user_id) {
            return true;
        }

        // Conference chairs can view
        if ($paper->conference->chairs()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // Reviewers assigned to this paper can view
        if ($paper->reviews()->where('reviewer_id', $user->id)->exists()) {
            return true;
        }

        // PC members who have accepted invitations can view papers from their assigned conferences
        if ($user->programCommittees()
            ->where('conference_id', $paper->conference_id)
            ->where('status', 'accepted')
            ->exists()) {
            return true;
        }

        // Admins can view
        return $user->role === 'admin';
    }

    public function create(User $user)
    {
        // Only authors can create papers
        return $user->role === 'author';
    }

    public function update(User $user, Paper $paper)
    {
        // Only author can update, and only if status is submitted
        return $user->id === $paper->user_id &&
               $paper->status === Paper::STATUS_SUBMITTED;
    }

    public function delete(User $user, Paper $paper)
    {
        // Only author can delete, and only if status is submitted
        return $user->id === $paper->user_id &&
               $paper->status === Paper::STATUS_SUBMITTED;
    }

    public function submit(User $user, Paper $paper)
    {
        // Only author can submit
        return $user->id === $paper->user_id;
    }

    public function review(User $user, Paper $paper)
    {
        // Only reviewers assigned to this paper can review
        return $paper->reviews()->where('reviewer_id', $user->id)->exists();
    }

    public function assignReviewers(User $user, Paper $paper)
    {
        return $user->isProgramChair($paper->conference) ||
            $user->isAreaChair($paper->conference) ||
            $user->role === 'admin';
    }

    public function viewReviews(User $user, Paper $paper)
    {
        // Author can view completed reviews
        if ($user->id === $paper->user_id) {
            return true;
        }

        // Reviewer can view their own reviews
        if ($paper->reviews()->where('reviewer_id', $user->id)->exists()) {
            return true;
        }

        // Chairs and admins can view all reviews
        return $user->can('assignReviewers', $paper);
    }

    public function uploadCameraReady(User $user, Paper $paper)
    {
        return $paper->authors->contains($user->id) &&
               $paper->status === 'accepted';
    }

    public function approveForProceedings(User $user, Paper $paper)
    {
        return $user->role === 'admin' &&
               $paper->status === 'accepted' &&
               $paper->camera_ready_path;
    }
}
