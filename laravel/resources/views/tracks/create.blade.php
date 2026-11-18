@extends('layouts.app')
@section('title', 'Add track')
@section('content')
  <h1>Add track</h1>
  <form method="post" action="{{ route('tracks.store') }}">
    @csrf
    @include('tracks._form')
    <button class="btn" type="submit">Insert</button>
  </form>
@endsection
