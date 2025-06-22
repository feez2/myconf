<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Models\Paper;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $data = [];

        if ($user->role === 'admin') {
            $data['conferences'] = Conference::count();
            $data['papers'] = Paper::count();
            $data['reviews'] = Review::count();
        } elseif ($user->role === 'author') {
            $data['papers'] = $user->papers()->count();
            $data['accepted'] = $user->papers()->where('status', 'accepted')->count();
        } elseif ($user->role === 'reviewer') {
            // Get conferences where user is an accepted PC member (same logic as ReviewController)
            $conferences = $user->programCommittees()
                ->where('status', 'accepted')
                ->pluck('conference_id');

            // Get all papers from these conferences
            $papers = Paper::whereIn('conference_id', $conferences);
            
            // Count completed reviews for papers in user's conferences
            $data['completed'] = $user->reviews()
                ->whereHas('paper', function($q) use ($conferences) {
                    $q->whereIn('conference_id', $conferences);
                })
                ->where('status', 'completed')
                ->count();
            
            // Count pending reviews for papers in user's conferences
            $data['pending'] = $user->reviews()
                ->whereHas('paper', function($q) use ($conferences) {
                    $q->whereIn('conference_id', $conferences);
                })
                ->where('status', 'pending')
                ->count();
            
            // Count papers that don't have reviews yet (not started)
            // This should be papers in the reviewer's conferences that don't have a review record for this reviewer
            $data['not_started'] = $papers->whereDoesntHave('reviews', function($q) use ($user) {
                $q->where('reviewer_id', $user->id);
            })->count();
            
            // Total reviews assigned to this reviewer for papers in their conferences
            $data['total_assigned'] = $user->reviews()
                ->whereHas('paper', function($q) use ($conferences) {
                    $q->whereIn('conference_id', $conferences);
                })
                ->count();
        }

        return view('dashboard', compact('data'));
    }
}
