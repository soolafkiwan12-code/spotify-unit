<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Spotify Laravel')</title>
  <style>
    :root { --green:#94d07f; --ink:#0a0a0a; --card:#151515; --muted:#a7a7a7; }
    * { box-sizing: border-box; }
    body { margin:0; font-family: Segoe UI, Tahoma, sans-serif; background:var(--ink); color:#fff; }
    a { color:var(--green); text-decoration:none; }
    header { display:flex; justify-content:space-between; gap:1rem; padding:1rem 4vw; background:#111; border-bottom:1px solid rgba(148,208,127,.25); }
    nav { display:flex; gap:1rem; flex-wrap:wrap; }
    main { padding:1.5rem 4vw; max-width:1100px; margin:0 auto; }
    .card, form, table { background:var(--card); border:1px solid #222; border-radius:12px; }
    form { display:grid; gap:.75rem; padding:1.2rem; max-width:460px; }
    input, select { padding:.7rem; border-radius:8px; border:1px solid #333; background:#0f0f0f; color:#fff; }
    .btn { border:0; border-radius:999px; padding:.7rem 1.1rem; background:var(--green); color:#0b1a0a; font-weight:700; cursor:pointer; }
    .ok { background:var(--green); color:#0b1a0a; padding:.7rem 1rem; border-radius:10px; }
    .err { color:#ff8a8a; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:.75rem; border-bottom:1px solid #222; text-align:left; }
    .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:1rem; }
    .stat { padding:1rem; } .stat strong { display:block; font-size:1.7rem; color:var(--green); }
    .toolbar { display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
  </style>
</head>
<body>
  <header>
    <strong>Spotify Laravel</strong>
    @auth
      <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('tracks.index') }}">Tracks</a>
        <a href="{{ route('artists.index') }}">Artists</a>
        <a href="{{ route('albums.index') }}">Albums</a>
      </nav>
      <form method="post" action="{{ route('logout') }}">
        @csrf
        <button class="btn" type="submit">Log out ({{ auth()->user()->name }})</button>
      </form>
    @endauth
  </header>
  <main>
    @if (session('ok')) <p class="ok">{{ session('ok') }}</p> @endif
    @yield('content')
  </main>
</body>
</html>
