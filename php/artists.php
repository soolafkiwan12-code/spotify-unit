<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_login();

$pageTitle = 'Artists';
$q = trim((string) ($_GET['q'] ?? ''));

$sql = 'SELECT ar.id, ar.name, ar.country, COUNT(a.id) AS albums_count
        FROM artists ar
        LEFT JOIN albums a ON a.artist_id = ar.id';
$params = [];

if ($q !== '') {
    $sql .= ' WHERE ar.name LIKE :q OR ar.country LIKE :q';
    $params['q'] = '%' . $q . '%';
}

$sql .= ' GROUP BY ar.id, ar.name, ar.country ORDER BY ar.name';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$artists = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<div class="toolbar">
  <h1>Artists</h1>
  <?php if (can_manage()): ?>
    <a class="btn" href="artist-form.php">Add artist</a>
  <?php endif; ?>
</div>

<form class="search" method="get">
  <input type="search" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search artist or country" />
  <button class="btn" type="submit">Search</button>
</form>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Country</th>
      <th>Albums</th>
      <?php if (can_manage()): ?><th></th><?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($artists as $artist): ?>
      <tr>
        <td><?= (int) $artist['id'] ?></td>
        <td><?= htmlspecialchars($artist['name']) ?></td>
        <td><?= htmlspecialchars((string) $artist['country']) ?></td>
        <td><?= (int) $artist['albums_count'] ?></td>
        <?php if (can_manage()): ?>
          <td class="actions">
            <a class="btn ghost" href="artist-form.php?id=<?= (int) $artist['id'] ?>">Edit</a>
            <form method="post" action="artist-delete.php" onsubmit="return confirm('Delete this artist and related albums/tracks?');">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>" />
              <input type="hidden" name="id" value="<?= (int) $artist['id'] ?>" />
              <button class="btn danger" type="submit">Delete</button>
            </form>
          </td>
        <?php endif; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>
