@extends('layouts.app')
@section('title', 'Tracks')
@section('content')
  <div class="toolbar">
    <h1>Tracks</h1>
    @if (auth()->user()->canManage())
      <a class="btn" href="{{ route('tracks.create') }}">Add track</a>
    @endif
  </div>
  <form method="get">
    <input type="search" name="q" value="{{ $q }}" placeholder="Search tracks" />
    <button class="btn" type="submit">Search</button>
  </form>
  <table>
    <thead><tr><th>Title</th><th>Artist</th><th>Album</th><th>Genre</th><th></th></tr></thead>
    <tbody>
      @foreach ($tracks as $track)
        <tr>
          <td>{{ $track->title }}</td>
          <td>{{ $track->album?->artist?->name }}</td>
          <td>{{ $track->album?->title }}</td>
          <td>{{ $track->genre }}</td>
          <td>
            @if (auth()->user()->canManage())
              <a href="{{ route('tracks.edit', $track) }}">Edit</a>
              <form method="post" action="{{ route('tracks.destroy', $track) }}" style="display:inline">
                @csrf @method('DELETE')
                <button class="btn" type="submit">Delete</button>
              </form>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {{ $tracks->links() }}
@endsection
