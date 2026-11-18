<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_manage();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$track = [
    'id' => 0,
    'album_id' => 0,
    'title' => '',
    'duration_seconds' => 180,
    'genre' => '',
];

if ($id > 0) {
    $stmt = db()->prepare('SELECT * FROM tracks WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if (!$found) {
        flash('error', 'Track not found.');
        header('Location: tracks.php');
        exit;
    }
    $track = $found;
}

$albums = db()->query(
    'SELECT a.id, a.title, ar.name AS artist_name
     FROM albums a
     INNER JOIN artists ar ON ar.id = a.artist_id
     ORDER BY ar.name, a.title'
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $albumId = (int) ($_POST['album_id'] ?? 0);
    $title = trim((string) ($_POST['title'] ?? ''));
    $duration = (int) ($_POST['duration_seconds'] ?? 0);
    $genre = trim((string) ($_POST['genre'] ?? ''));

    if ($albumId <= 0 || $title === '' || $duration <= 0) {
        flash('error', 'Album, title, and duration are required.');
    } elseif ($id > 0) {
        $sql = 'UPDATE tracks
                SET album_id = :album_id, title = :title, duration_seconds = :duration_seconds, genre = :genre
                WHERE id = :id';
        db()->prepare($sql)->execute([
            'album_id' => $albumId,
            'title' => $title,
            'duration_seconds' => $duration,
            'genre' => $genre ?: null,
            'id' => $id,
        ]);
        flash('ok', 'Track updated.');
        header('Location: tracks.php');
        exit;
    } else {
        $sql = 'INSERT INTO tracks (album_id, title, duration_seconds, genre)
                VALUES (:album_id, :title, :duration_seconds, :genre)';
        db()->prepare($sql)->execute([
            'album_id' => $albumId,
            'title' => $title,
            'duration_seconds' => $duration,
            'genre' => $genre ?: null,
        ]);
        flash('ok', 'Track added.');
        header('Location: tracks.php');
        exit;
    }
}

$pageTitle = $id ? 'Edit track' : 'Add track';
require __DIR__ . '/includes/header.php';
?>
<h1><?= $id ? 'Edit track' : 'Add track' ?></h1>
<form class="form" method="post">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>" />
  <label>Album
    <select name="album_id" required>
      <option value="">Select album</option>
      <?php foreach ($albums as $album): ?>
        <option value="<?= (int) $album['id'] ?>" <?= (int) $track['album_id'] === (int) $album['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($album['artist_name'] . ' — ' . $album['title']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Title
    <input type="text" name="title" required value="<?= htmlspecialchars((string) $track['title']) ?>" />
  </label>
  <label>Duration (seconds)
    <input type="number" name="duration_seconds" min="1" required value="<?= (int) $track['duration_seconds'] ?>" />
  </label>
  <label>Genre
    <input type="text" name="genre" value="<?= htmlspecialchars((string) $track['genre']) ?>" />
  </label>
  <button class="btn" type="submit"><?= $id ? 'Update' : 'Insert' ?></button>
  <a class="btn ghost" href="tracks.php">Cancel</a>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
