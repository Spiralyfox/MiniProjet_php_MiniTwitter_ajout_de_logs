<?php
require_once __DIR__ . '/helpers.php';
include __DIR__ . '/partials_header.php';
$me = current_user();
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $stmt = $pdo->prepare('SELECT id, username, bio, avatar_url FROM users WHERE username LIKE ? ORDER BY username ASC LIMIT 100');
    $stmt->execute(['%'.$q.'%']);
} else {
    $stmt = $pdo->query('SELECT id, username, bio, avatar_url FROM users ORDER BY created_at DESC LIMIT 100');
}
$users = $stmt->fetchAll();
?>
<section class="card">
  <h2 class="section-title">Utilisateurs</h2>
  <?php if (!$users): ?>
    <p class="empty">Aucun utilisateur trouvé.</p>
  <?php endif; ?>
  <?php foreach ($users as $u): if ($me && $u['id'] == $me['id']) continue; ?>
    <div class="user-row">
      <div style="display:flex;align-items:center;gap:10px">
        <?php if ($u['avatar_url']): ?><img class="avatar" src="<?php echo h($u['avatar_url']); ?>" alt=""><?php endif; ?>
        <div>
          <a href="/user.php?u=<?php echo urlencode($u['username']); ?>">@<?php echo h($u['username']); ?></a>
          <div class="muted" style="color:var(--muted);max-width:600px;overflow:hidden;text-overflow:ellipsis"><?php echo h($u['bio']); ?></div>
        </div>
      </div>
      <div>
        <?php if ($me): ?>
          <form method="post" action="/follow.php" style="display:inline">
            <?php csrf_input(); ?>
            <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
            <?php if (is_following($me['id'], $u['id'])): ?>
              <input type="hidden" name="action" value="unfollow">
              <button class="button secondary" type="submit">Ne plus suivre</button>
            <?php else: ?>
              <input type="hidden" name="action" value="follow">
              <button class="button" type="submit">Suivre</button>
            <?php endif; ?>
          </form>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</section>
<?php include __DIR__ . '/partials_footer.php'; ?>
