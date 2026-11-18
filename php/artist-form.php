<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_manage();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$artist = ['id' => 0, 'name' => '', 'country' => '', 'bio' => ''];

if ($id > 0) {
    $stmt = db()->prepare('SELECT * FROM artists WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if (!$found) {
        flash('error', 'Artist not found.');
        header('Location: artists.php');
        exit;
    }
    $artist = $found;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim((string) ($_POST['name'] ?? ''));
    $country = trim((string) ($_POST['country'] ?? ''));
    $bio = trim((string) ($_POST['bio'] ?? ''));

    if ($name === '') {
        flash('error', 'Artist name is required.');
    } elseif ($id > 0) {
        db()->prepare('UPDATE artists SET name = :name, country = :country, bio = :bio WHERE id = :id')
            ->execute([
                'name' => $name,
                'country' => $country ?: null,
                'bio' => $bio ?: null,
                'id' => $id,
            ]);
        flash('ok', 'Artist updated.');
        header('Location: artists.php');
        exit;
    } else {
        db()->prepare('INSERT INTO artists (name, country, bio) VALUES (:name, :country, :bio)')
            ->execute([
                'name' => $name,
                'country' => $country ?: null,
                'bio' => $bio ?: null,
            ]);
        flash('ok', 'Artist added.');
        header('Location: artists.php');
        exit;
    }
}

$pageTitle = $id ? 'Edit artist' : 'Add artist';
require __DIR__ . '/includes/header.php';
?>
<h1><?= $id ? 'Edit artist' : 'Add artist' ?></h1>
<form class="form" method="post">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>" />
  <label>Name
    <input type="text" name="name" required value="<?= htmlspecialchars((string) $artist['name']) ?>" />
  </label>
  <label>Country
    <input type="text" name="country" value="<?= htmlspecialchars((string) $artist['country']) ?>" />
  </label>
  <label>Bio
    <textarea name="bio" rows="4"><?= htmlspecialchars((string) $artist['bio']) ?></textarea>
  </label>
  <button class="btn" type="submit"><?= $id ? 'Update' : 'Insert' ?></button>
  <a class="btn ghost" href="artists.php">Cancel</a>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
