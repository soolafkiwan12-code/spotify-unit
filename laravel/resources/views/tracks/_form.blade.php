<label>Album
  <select name="album_id" required>
    @foreach ($albums as $album)
      <option value="{{ $album->id }}" @selected(old('album_id', $track->album_id ?? '') == $album->id)>
        {{ $album->artist?->name }} — {{ $album->title }}
      </option>
    @endforeach
  </select>
</label>
<label>Title <input name="title" value="{{ old('title', $track->title ?? '') }}" required></label>
<label>Duration <input type="number" name="duration_seconds" value="{{ old('duration_seconds', $track->duration_seconds ?? 180) }}" required></label>
<label>Genre <input name="genre" value="{{ old('genre', $track->genre ?? '') }}"></label>
@error('title') <p class="err">{{ $message }}</p> @enderror
