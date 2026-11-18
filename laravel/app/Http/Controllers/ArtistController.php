<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtistRequest;
use App\Models\Artist;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArtistController extends Controller
{
    public function index(): View
    {
        return view('artists.index', [
            'artists' => Artist::withCount('albums')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()->canManage(), 403);

        return view('artists.create');
    }

    public function store(StoreArtistRequest $request): RedirectResponse
    {
        Artist::create($request->validated());

        return redirect()->route('artists.index')->with('ok', 'Artist added.');
    }

    public function edit(Artist $artist): View
    {
        abort_unless(auth()->user()->canManage(), 403);

        return view('artists.edit', compact('artist'));
    }

    public function update(StoreArtistRequest $request, Artist $artist): RedirectResponse
    {
        $artist->update($request->validated());

        return redirect()->route('artists.index')->with('ok', 'Artist updated.');
    }

    public function destroy(Artist $artist): RedirectResponse
    {
        abort_unless(auth()->user()->canManage(), 403);
        $artist->delete();

        return redirect()->route('artists.index')->with('ok', 'Artist deleted.');
    }
}
