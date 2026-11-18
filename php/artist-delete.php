<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_manage();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: artists.php');
    exit;
}

csrf_check();
$id = (int) ($_POST['id'] ?? 0);

if ($id > 0) {
    db()->prepare('DELETE FROM artists WHERE id = :id')->execute(['id' => $id]);
    flash('ok', 'Artist deleted.');
}

header('Location: artists.php');
exit;
