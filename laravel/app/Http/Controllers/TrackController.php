<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrackRequest;
use App\Models\Album;
use App\Models\Track;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $tracks = Track::query()
            ->with('album.artist')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('genre', 'like', "%{$q}%")
                    ->orWhereHas('album.artist', fn ($artist) => $artist->where('name', 'like', "%{$q}%"));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('tracks.index', compact('tracks', 'q'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->canManage(), 403);

        return view('tracks.create', [
            'albums' => Album::with('artist')->orderBy('title')->get(),
        ]);
    }

    public function store(StoreTrackRequest $request): RedirectResponse
    {
        Track::create($request->validated());

        return redirect()->route('tracks.index')->with('ok', 'Track added.');
    }

    public function edit(Track $track): View
    {
        abort_unless(auth()->user()->canManage(), 403);

        return view('tracks.edit', [
            'track' => $track,
            'albums' => Album::with('artist')->orderBy('title')->get(),
        ]);
    }

    public function update(StoreTrackRequest $request, Track $track): RedirectResponse
    {
        $track->update($request->validated());

        return redirect()->route('tracks.index')->with('ok', 'Track updated.');
    }

    public function destroy(Track $track): RedirectResponse
    {
        abort_unless(auth()->user()->canManage(), 403);
        $track->delete();

        return redirect()->route('tracks.index')->with('ok', 'Track deleted.');
    }
}
