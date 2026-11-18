@extends('layouts.app')
@section('title', 'Login')
@section('content')
  <h1>Laravel Authentication</h1>
  <p>admin@spotify.test / Admin@123</p>
  <form method="post" action="{{ route('login') }}">
    @csrf
    <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
    <label>Password <input type="password" name="password" required></label>
    @error('email') <p class="err">{{ $message }}</p> @enderror
    <button class="btn" type="submit">Log in</button>
  </form>
@endsection
