<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? 'Dashboard';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?> — <?= APP_NAME ?></title>
  <link rel="stylesheet" href="assets/admin.css" />
</head>
<body>
  <header class="top">
    <a class="brand" href="dashboard.php">Spotify PHP</a>
    <?php if ($user): ?>
      <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="tracks.php">Tracks</a>
        <a href="artists.php">Artists</a>
        <a href="search.php">Search</a>
        <a href="../index.html">Website</a>
      </nav>
      <div class="who">
        <span><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
        <a href="logout.php">Log out</a>
      </div>
    <?php endif; ?>
  </header>
  <main class="page">
    <?php if ($msg = flash('ok')): ?>
      <p class="alert ok"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
      <p class="alert err"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
