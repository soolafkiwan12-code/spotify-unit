@extends('layouts.app')
@section('title', 'Edit artist')
@section('content')
  <h1>Edit artist</h1>
  <form method="post" action="{{ route('artists.update', $artist) }}">
    @csrf @method('PUT')
    <label>Name <input name="name" value="{{ old('name', $artist->name) }}" required></label>
    <label>Country <input name="country" value="{{ old('country', $artist->country) }}"></label>
    <label>Bio <input name="bio" value="{{ old('bio', $artist->bio) }}"></label>
    <button class="btn" type="submit">Update</button>
  </form>
@endsection
