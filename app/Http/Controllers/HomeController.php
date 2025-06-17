<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;

class HomeController extends Controller
{
    public function index()
    {
        $conferences = Conference::where('status', 'upcoming')
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        return view('home', compact('conferences'));
    }
}
