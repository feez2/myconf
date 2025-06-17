<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Review $review)
    {
        // Reviewer can view their own review
        if ($user->id === $review->reviewer_id) {
            return true;
        }

        // Paper author can view completed reviews
        if ($user->id === $review->paper->user_id && $review->isCompleted()) {
            return true;
        }

        // Conference chairs and admins can view
        return $user->can('manageReviews', $review->paper->conference) ||
               $user->role === 'admin';
    }

    public function update(User $user, Review $review)
    {
        // Only reviewer can update, and only if not completed
        return $user->id === $review->reviewer_id &&
               !$review->isCompleted();
    }

    public function delete(User $user, Review $review)
    {
        // Only admins and program chairs can delete
        return $user->role === 'admin' ||
               $user->isProgramChair($review->paper->conference);
    }
}
