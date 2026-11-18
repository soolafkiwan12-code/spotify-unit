<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

if (current_user()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } elseif (!attempt_login($email, $password)) {
        $error = 'Invalid email or password.';
    } else {
        header('Location: dashboard.php');
        exit;
    }
}

$pageTitle = 'Log in';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log in — <?= APP_NAME ?></title>
  <link rel="stylesheet" href="assets/admin.css" />
</head>
<body>
  <main class="page">
    <h1>PHP 8 Login</h1>
    <p class="note">Sessions + roles. Demo: admin@spotify.test / Admin@123</p>
    <?php if ($error): ?>
      <p class="alert err"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form class="form" method="post">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>" />
      <label>Email
        <input type="email" name="email" required autocomplete="username" />
      </label>
      <label>Password
        <input type="password" name="password" required autocomplete="current-password" />
      </label>
      <button class="btn" type="submit">Log in</button>
      <a class="btn ghost" href="../index.html">Back to website</a>
    </form>
    <p class="note">editor@spotify.test / Editor@123 · user@spotify.test / User@123</p>
  </main>
</body>
</html>
