<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Track;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'users' => User::count(),
            'artists' => Artist::count(),
            'albums' => Album::count(),
            'tracks' => Track::count(),
            'popular' => Track::with('album.artist')
                ->withCount('likedBy')
                ->orderByDesc('liked_by_count')
                ->limit(5)
                ->get(),
        ]);
    }
}
