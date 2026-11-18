<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrackRequest;
use App\Models\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        $tracks = Track::query()
            ->with('album.artist')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('genre', 'like', "%{$q}%");
            })
            ->latest()
            ->get()
            ->map(fn (Track $track) => $this->payload($track));

        return response()->json($tracks);
    }

    public function store(StoreTrackRequest $request): JsonResponse
    {
        $track = Track::create($request->validated())->load('album.artist');

        return response()->json($this->payload($track), 201);
    }

    public function show(Track $track): JsonResponse
    {
        return response()->json($this->payload($track->load('album.artist')));
    }

    public function update(StoreTrackRequest $request, Track $track): JsonResponse
    {
        $track->update($request->validated());

        return response()->json($this->payload($track->fresh()->load('album.artist')));
    }

    public function destroy(Track $track): JsonResponse
    {
        abort_unless(request()->user()?->canManage(), 403);
        $track->delete();

        return response()->json(['message' => 'Deleted.']);
    }

    private function payload(Track $track): array
    {
        return [
            'id' => $track->id,
            'title' => $track->title,
            'genre' => $track->genre,
            'duration_seconds' => $track->duration_seconds,
            'album_id' => $track->album_id,
            'album' => $track->album?->title,
            'artist' => $track->album?->artist?->name,
        ];
    }
}
