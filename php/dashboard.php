<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_login();

$pageTitle = 'Dashboard';

$counts = [
    'users' => (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'artists' => (int) db()->query('SELECT COUNT(*) FROM artists')->fetchColumn(),
    'albums' => (int) db()->query('SELECT COUNT(*) FROM albums')->fetchColumn(),
    'tracks' => (int) db()->query('SELECT COUNT(*) FROM tracks')->fetchColumn(),
];

$popular = db()->query(
    'SELECT t.title, ar.name AS artist_name, COUNT(l.user_id) AS likes_count
     FROM tracks t
     INNER JOIN albums a ON a.id = t.album_id
     INNER JOIN artists ar ON ar.id = a.artist_id
     LEFT JOIN likes l ON l.track_id = t.id
     GROUP BY t.id, t.title, ar.name
     ORDER BY likes_count DESC, t.title
     LIMIT 5'
)->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<h1>Dashboard</h1>
<p class="note">Welcome <?= htmlspecialchars(current_user()['name']) ?>. Role: <?= htmlspecialchars(current_user()['role']) ?>.</p>

<section class="stats">
  <?php foreach ($counts as $label => $value): ?>
    <article class="card stat">
      <span><?= htmlspecialchars($label) ?></span>
      <strong><?= $value ?></strong>
    </article>
  <?php endforeach; ?>
</section>

<h2>Top tracks (JOIN + COUNT)</h2>
<table>
  <thead>
    <tr><th>Track</th><th>Artist</th><th>Likes</th></tr>
  </thead>
  <tbody>
    <?php foreach ($popular as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['artist_name']) ?></td>
        <td><?= (int) $row['likes_count'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>
