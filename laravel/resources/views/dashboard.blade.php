@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
  <h1>Dashboard</h1>
  <p>Signed in as {{ auth()->user()->name }} ({{ auth()->user()->role?->name }})</p>
  <section class="stats">
    <article class="card stat"><span>Users</span><strong>{{ $users }}</strong></article>
    <article class="card stat"><span>Artists</span><strong>{{ $artists }}</strong></article>
    <article class="card stat"><span>Albums</span><strong>{{ $albums }}</strong></article>
    <article class="card stat"><span>Tracks</span><strong>{{ $tracks }}</strong></article>
  </section>
  <h2>Popular tracks</h2>
  <table>
    <thead><tr><th>Title</th><th>Artist</th><th>Likes</th></tr></thead>
    <tbody>
      @foreach ($popular as $track)
        <tr>
          <td>{{ $track->title }}</td>
          <td>{{ $track->album?->artist?->name }}</td>
          <td>{{ $track->liked_by_count }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
