<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_login();

$pageTitle = 'Tracks';
$q = trim((string) ($_GET['q'] ?? ''));

$sql = 'SELECT t.id, t.title, t.genre, t.duration_seconds, a.title AS album_title, ar.name AS artist_name
        FROM tracks t
        INNER JOIN albums a ON a.id = t.album_id
        INNER JOIN artists ar ON ar.id = a.artist_id';
$params = [];

if ($q !== '') {
    $sql .= ' WHERE t.title LIKE :q OR ar.name LIKE :q OR a.title LIKE :q OR t.genre LIKE :q';
    $params['q'] = '%' . $q . '%';
}

$sql .= ' ORDER BY t.id DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$tracks = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<div class="toolbar">
  <h1>Tracks</h1>
  <?php if (can_manage()): ?>
    <a class="btn" href="track-form.php">Add track</a>
  <?php endif; ?>
</div>

<form class="search" method="get">
  <input type="search" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search title, artist, album, genre" />
  <button class="btn" type="submit">Search</button>
</form>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Artist</th>
      <th>Album</th>
      <th>Genre</th>
      <th>Duration</th>
      <?php if (can_manage()): ?><th></th><?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tracks as $track): ?>
      <tr>
        <td><?= (int) $track['id'] ?></td>
        <td><?= htmlspecialchars($track['title']) ?></td>
        <td><?= htmlspecialchars($track['artist_name']) ?></td>
        <td><?= htmlspecialchars($track['album_title']) ?></td>
        <td><?= htmlspecialchars((string) $track['genre']) ?></td>
        <td><?= (int) $track['duration_seconds'] ?>s</td>
        <?php if (can_manage()): ?>
          <td class="actions">
            <a class="btn ghost" href="track-form.php?id=<?= (int) $track['id'] ?>">Edit</a>
            <form method="post" action="track-delete.php" onsubmit="return confirm('Delete this track?');">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>" />
              <input type="hidden" name="id" value="<?= (int) $track['id'] ?>" />
              <button class="btn danger" type="submit">Delete</button>
            </form>
          </td>
        <?php endif; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php if (!$tracks): ?>
  <p class="note">No tracks found.</p>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
