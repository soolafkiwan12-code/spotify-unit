@extends('layouts.app')
@section('title', 'Artists')
@section('content')
  <div class="toolbar">
    <h1>Artists</h1>
    @if (auth()->user()->canManage())
      <a class="btn" href="{{ route('artists.create') }}">Add artist</a>
    @endif
  </div>
  <table>
    <thead><tr><th>Name</th><th>Country</th><th>Albums</th><th></th></tr></thead>
    <tbody>
      @foreach ($artists as $artist)
        <tr>
          <td>{{ $artist->name }}</td>
          <td>{{ $artist->country }}</td>
          <td>{{ $artist->albums_count }}</td>
          <td>
            @if (auth()->user()->canManage())
              <a href="{{ route('artists.edit', $artist) }}">Edit</a>
              <form method="post" action="{{ route('artists.destroy', $artist) }}" style="display:inline">
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
