@extends('layouts.app')
@section('title', 'Albums')
@section('content')
  <div class="toolbar">
    <h1>Albums</h1>
    @if (auth()->user()->canManage())
      <a class="btn" href="{{ route('albums.create') }}">Add album</a>
    @endif
  </div>
  <table>
    <thead><tr><th>Title</th><th>Artist</th><th>Year</th><th>Tracks</th><th></th></tr></thead>
    <tbody>
      @foreach ($albums as $album)
        <tr>
          <td>{{ $album->title }}</td>
          <td>{{ $album->artist?->name }}</td>
          <td>{{ $album->release_year }}</td>
          <td>{{ $album->tracks_count }}</td>
          <td>
            @if (auth()->user()->canManage())
              <a href="{{ route('albums.edit', $album) }}">Edit</a>
              <form method="post" action="{{ route('albums.destroy', $album) }}" style="display:inline">
                @csrf @method('DELETE')
                <button class="btn" type="submit">Delete</button>
              </form>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
