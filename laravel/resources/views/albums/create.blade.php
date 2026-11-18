@extends('layouts.app')
@section('title', 'Add album')
@section('content')
  <h1>Add album</h1>
  <form method="post" action="{{ route('albums.store') }}">
    @csrf
    <label>Artist
      <select name="artist_id" required>
        @foreach ($artists as $artist)
          <option value="{{ $artist->id }}">{{ $artist->name }}</option>
        @endforeach
      </select>
    </label>
    <label>Title <input name="title" required></label>
    <label>Year <input type="number" name="release_year" value="2024"></label>
    <button class="btn" type="submit">Insert</button>
  </form>
@endsection
