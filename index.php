<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/logger.php';
include __DIR__ . '/partials_header.php';

$user = current_user();

if ($user && $_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $content = trim($_POST['content'] ?? '');
    if ($content !== '' && mb_strlen($content) <= 280) {
        $stmt = $pdo->prepare('INSERT INTO posts (user_id, content) VALUES (?, ?)');
        $stmt->execute([$user['id'], $content]);
        header('Location: /');

        $ip = $_SERVER['REMOTE_ADDR'];
        write_log("INFO", $ip, $user['username'], "Publie un post. ID : ".$pdo->lastInsertId());

        exit;
    } else {
        $error = "Le message doit faire entre 1 et 280 caractères.";
    }
}

?>
<div class="grid">
  <section class="card">
    <h2 class="section-title">Fil d'actualités</h2>
    <?php if ($user): ?>
      <form method="post" class="card" style="margin-bottom:16px">
        <?php csrf_input(); ?>
        <label for="content">Exprimez-vous (280 max)</label>
        <textarea id="content" name="content" maxlength="280" placeholder="Quoi de neuf ?"></textarea>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px">
          <span class="muted"><?php echo isset($error) ? '<span style="color:#ef4444">'.h($error).'</span>' : ''; ?></span>
          <button class="button" type="submit">Publier</button>
        </div>
      </form>
    <?php else: ?>
      <p class="empty">Connectez-vous pour publier et suivre des gens.</p>
    <?php endif; ?>

    <?php
      if ($user) {
        $stmt = $pdo->prepare('
          SELECT p.*, u.username, u.avatar_url
          FROM posts p
          JOIN users u ON u.id = p.user_id
          WHERE p.user_id = :me OR p.user_id IN (
            SELECT followee_id FROM follows WHERE follower_id = :me
          )
          ORDER BY p.created_at DESC
          LIMIT 100
        ');
        $stmt->execute([':me' => $user['id']]);
      } else {
        $stmt = $pdo->query('
          SELECT p.*, u.username, u.avatar_url
          FROM posts p JOIN users u ON u.id = p.user_id
          ORDER BY p.created_at DESC
          LIMIT 50
        ');
      }
      $posts = $stmt->fetchAll();
      if (!$posts): ?>
        <p class="empty">Aucun post pour le moment.</p>
      <?php else:
        foreach ($posts as $post): ?>
          <article class="post">
            <div class="meta">
              <?php if ($post['avatar_url']): ?>
                <img class="avatar" src="<?php echo h($post['avatar_url']); ?>" alt="">
              <?php endif; ?>
              <a href="/user.php?u=<?php echo urlencode($post['username']); ?>">@<?php echo h($post['username']); ?></a>
              · <span><?php echo h(time_ago($post['created_at'])); ?></span>
            </div>
            <div class="content"><?php echo nl2br(h($post['content'])); ?></div>
          </article>
    <?php endforeach; endif; ?>
  </section>

  <aside class="card">
    <h3 class="section-title">Découvrir</h3>
    <p class="empty">Trouvez des personnes à suivre et personnalisez votre fil.</p>
    <p><a class="button secondary" href="/users.php">Explorer les utilisateurs</a></p>
  </aside>
</div>
<?php include __DIR__ . '/partials_footer.php'; ?>
