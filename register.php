<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/logger.php';
if (current_user()) { header('Location: /'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = [];
    if (!preg_match('/^[a-zA-Z0-9_]{3,32}$/', $username)) $errors[] = "Nom d'utilisateur invalide (3-32 alphanumériques/underscore).";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (strlen($password) < 6) $errors[] = "Mot de passe trop court (min 6).";

    if (!$errors) {
        // Check uniqueness
        $stmt = $pdo->prepare('SELECT 1 FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) $errors[] = "Nom d'utilisateur ou email déjà utilisé.";
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, $hash]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: /');

        $ip = $_SERVER['REMOTE_ADDR'];
        write_log("INFO", $ip, $username, "Nouvel utilisateur inscrit : " . $username . " (Email: " . $email . ")");

        exit;
    }
}

include __DIR__ . '/partials_header.php';
?>
<section class="card" style="max-width:520px;margin:40px auto;">
  <h2 class="section-title">Créer un compte</h2>
  <?php if (!empty($errors)): ?>
    <div class="card" style="border-left:4px solid #ef4444;background:rgba(239,68,68,.08)">
      <ul><?php foreach ($errors as $e) echo '<li>'.h($e).'</li>'; ?></ul>
    </div>
  <?php endif; ?>
  <form method="post">
    <?php csrf_input(); ?>
    <label>Nom d'utilisateur</label>
    <input name="username" value="<?php echo h($_POST['username'] ?? ''); ?>" required>
    <label>Email</label>
    <input type="email" name="email" value="<?php echo h($_POST['email'] ?? ''); ?>" required>
    <label>Mot de passe</label>
    <input type="password" name="password" required>
    <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
      <a href="/login.php">Déjà inscrit ?</a>
      <button class="button" type="submit">Inscription</button>
    </div>
  </form>
</section>
<?php include __DIR__ . '/partials_footer.php'; ?>
