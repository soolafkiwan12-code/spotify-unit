@extends('layouts.app')
@section('title', 'Edit album')
@section('content')
  <h1>Edit album</h1>
  <form method="post" action="{{ route('albums.update', $album) }}">
    @csrf @method('PUT')
    <label>Artist
      <select name="artist_id" required>
        @foreach ($artists as $artist)
          <option value="{{ $artist->id }}" @selected($album->artist_id == $artist->id)>{{ $artist->name }}</option>
        @endforeach
      </select>
    </label>
    <label>Title <input name="title" value="{{ $album->title }}" required></label>
    <label>Year <input type="number" name="release_year" value="{{ $album->release_year }}"></label>
    <button class="btn" type="submit">Update</button>
  </form>
@endsection
