<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_login();

$pageTitle = 'Search';
$q = trim((string) ($_GET['q'] ?? ''));
$results = [];

if ($q !== '') {
    $sql = 'SELECT t.id, t.title AS track_title, t.genre, a.title AS album_title, ar.name AS artist_name
            FROM tracks t
            INNER JOIN albums a ON a.id = t.album_id
            INNER JOIN artists ar ON ar.id = a.artist_id
            WHERE t.title LIKE :q
               OR a.title LIKE :q
               OR ar.name LIKE :q
               OR t.genre LIKE :q
            ORDER BY ar.name, t.title';
    $stmt = db()->prepare($sql);
    $stmt->execute(['q' => '%' . $q . '%']);
    $results = $stmt->fetchAll();
}

require __DIR__ . '/includes/header.php';
?>
<h1>Search catalog</h1>
<form class="search" method="get">
  <input type="search" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Track, album, artist, genre" />
  <button class="btn" type="submit">Search</button>
</form>

<?php if ($q === ''): ?>
  <p class="note">Type a keyword. This page uses SELECT + JOIN + LIKE.</p>
<?php elseif (!$results): ?>
  <p class="note">No results for “<?= htmlspecialchars($q) ?>”.</p>
<?php else: ?>
  <p class="note"><?= count($results) ?> result(s).</p>
  <table>
    <thead>
      <tr><th>Track</th><th>Artist</th><th>Album</th><th>Genre</th></tr>
    </thead>
    <tbody>
      <?php foreach ($results as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['track_title']) ?></td>
          <td><?= htmlspecialchars($row['artist_name']) ?></td>
          <td><?= htmlspecialchars($row['album_title']) ?></td>
          <td><?= htmlspecialchars((string) $row['genre']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
