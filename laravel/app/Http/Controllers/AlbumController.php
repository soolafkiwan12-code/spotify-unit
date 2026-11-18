<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlbumController extends Controller
{
    public function index(): View
    {
        return view('albums.index', [
            'albums' => Album::with('artist')->withCount('tracks')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()->canManage(), 403);

        return view('albums.create', ['artists' => Artist::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canManage(), 403);

        $data = $request->validate([
            'artist_id' => ['required', 'exists:artists,id'],
            'title' => ['required', 'string', 'max:150'],
            'release_year' => ['nullable', 'integer', 'min:1950', 'max:2030'],
        ]);

        Album::create($data);

        return redirect()->route('albums.index')->with('ok', 'Album added.');
    }

    public function edit(Album $album): View
    {
        abort_unless(auth()->user()->canManage(), 403);

        return view('albums.edit', [
            'album' => $album,
            'artists' => Artist::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Album $album): RedirectResponse
    {
        abort_unless(auth()->user()->canManage(), 403);

        $album->update($request->validate([
            'artist_id' => ['required', 'exists:artists,id'],
            'title' => ['required', 'string', 'max:150'],
            'release_year' => ['nullable', 'integer', 'min:1950', 'max:2030'],
        ]));

        return redirect()->route('albums.index')->with('ok', 'Album updated.');
    }

    public function destroy(Album $album): RedirectResponse
    {
        abort_unless(auth()->user()->canManage(), 403);
        $album->delete();

        return redirect()->route('albums.index')->with('ok', 'Album deleted.');
    }
}
