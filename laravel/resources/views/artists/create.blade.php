@extends('layouts.app')
@section('title', isset($artist) ? 'Edit artist' : 'Add artist')
@section('content')
  <h1>{{ isset($artist) ? 'Edit artist' : 'Add artist' }}</h1>
  <form method="post" action="{{ isset($artist) ? route('artists.update', $artist) : route('artists.store') }}">
    @csrf
    @isset($artist) @method('PUT') @endisset
    <label>Name <input name="name" value="{{ old('name', $artist->name ?? '') }}" required></label>
    <label>Country <input name="country" value="{{ old('country', $artist->country ?? '') }}"></label>
    <label>Bio <input name="bio" value="{{ old('bio', $artist->bio ?? '') }}"></label>
    <button class="btn" type="submit">{{ isset($artist) ? 'Update' : 'Insert' }}</button>
  </form>
@endsection
