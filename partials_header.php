<?php
require_once __DIR__ . '/helpers.php';
$user = current_user();
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MiniTwit</title>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
<header class="nav">
  <div class="container nav-inner">
    <a class="brand" href="/">MiniTwit</a>
    <form class="search" method="get" action="/users.php">
      <input type="text" name="q" placeholder="Rechercher des utilisateurs…" value="<?php echo h($_GET['q'] ?? ''); ?>">
    </form>
    <nav class="menu">
      <?php if ($user): ?>
        <a href="/users.php">Utilisateurs</a>
        <a href="/profile.php">Profil</a>
        <a class="btn" href="/logout.php">Déconnexion</a>
        <span class="avatar-tag">
          <?php if ($user['avatar_url']): ?>
            <img class="avatar" src="<?php echo h($user['avatar_url']); ?>" alt="avatar">
          <?php endif; ?>
          @<?php echo h($user['username']); ?>
        </span>
      <?php else: ?>
        <a href="/login.php">Connexion</a>
        <a class="btn" href="/register.php">Inscription</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
