<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/logger.php';
if (current_user()) { header('Location: /'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $username_or_email = trim($_POST['user'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username_or_email, $username_or_email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: /');

        $ip = $_SERVER['REMOTE_ADDR'];
        write_log("INFO", $ip, $user['username'], "S'est connecté");

        exit;
    } else {
        $error = "Identifiants invalides.";
    }
}

include __DIR__ . '/partials_header.php';
?>
<section class="card" style="max-width:520px;margin:40px auto;">
  <h2 class="section-title">Connexion</h2>
  <?php if (!empty($error)): ?>
    <div class="card" style="border-left:4px solid #ef4444;background:rgba(239,68,68,.08)"><?php echo h($error); ?></div>
  <?php endif; ?>
  <form method="post">
    <?php csrf_input(); ?>
    <label>Nom d'utilisateur ou email</label>
    <input name="user" required>
    <label>Mot de passe</label>
    <input type="password" name="password" required>
    <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
      <a href="/register.php">Créer un compte</a>
      <button class="button" type="submit">Se connecter</button>
    </div>
  </form>
</section>
<?php include __DIR__ . '/partials_footer.php'; ?>
