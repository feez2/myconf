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
            $data['reviews'] = $user->reviews()->count();
            $data['pending'] = $user->reviews()->where('status', 'accepted')->count();
            $data['requested'] = $user->reviews()->where('status', 'requested')->count();
            $data['completed'] = $user->reviews()->where('status', 'completed')->count();
        }

        return view('dashboard', compact('data'));
    }
}
