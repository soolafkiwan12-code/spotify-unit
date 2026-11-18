@extends('layouts.app')
@section('title', 'Edit track')
@section('content')
  <h1>Edit track</h1>
  <form method="post" action="{{ route('tracks.update', $track) }}">
    @csrf @method('PUT')
    @include('tracks._form')
    <button class="btn" type="submit">Update</button>
  </form>
@endsection
