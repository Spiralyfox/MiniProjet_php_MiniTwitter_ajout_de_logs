<?php
require_once __DIR__ . '/helpers.php';
include __DIR__ . '/partials_header.php';

$username = trim($_GET['u'] ?? '');
$u = $username ? find_user_by_username($username) : null;

if (!$u) {
    echo '<section class="card"><p class="empty">Utilisateur introuvable.</p></section>';
    include __DIR__ . '/partials_footer.php';
    exit;
}

$me = current_user();
$is_following = $me ? is_following($me['id'], $u['id']) : false;

$stmt = $pdo->prepare('SELECT p.*, u.username, u.avatar_url FROM posts p JOIN users u ON u.id = p.user_id WHERE p.user_id = ? ORDER BY p.created_at DESC LIMIT 100');
$stmt->execute([$u['id']]);
$posts = $stmt->fetchAll();
?>
<section class="card">
  <div style="display:flex;align-items:center;justify-content:space-between;gap:16px">
    <div style="display:flex;align-items:center;gap:12px">
      <?php if ($u['avatar_url']): ?><img class="avatar" style="width:48px;height:48px" src="<?php echo h($u['avatar_url']); ?>"><?php endif; ?>
      <div>
        <h2 class="section-title" style="margin:0">@<?php echo h($u['username']); ?></h2>
        <div class="muted" style="max-width:640px"><?php echo h($u['bio']); ?></div>
      </div>
    </div>
    <?php if ($me && $me['id'] !== $u['id']): ?>
      <form method="post" action="/follow.php">
        <?php csrf_input(); ?>
        <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
        <?php if ($is_following): ?>
          <input type="hidden" name="action" value="unfollow">
          <button class="button secondary" type="submit">Ne plus suivre</button>
        <?php else: ?>
          <input type="hidden" name="action" value="follow">
          <button class="button" type="submit">Suivre</button>
        <?php endif; ?>
      </form>
    <?php endif; ?>
  </div>
</section>

<section class="card">
  <h3 class="section-title">Posts</h3>
  <?php if (!$posts): ?>
    <p class="empty">Aucun post.</p>
  <?php else: foreach ($posts as $post): ?>
    <article class="post">
      <div class="meta">
        <?php if ($post['avatar_url']): ?><img class="avatar" src="<?php echo h($post['avatar_url']); ?>" alt=""><?php endif; ?>
        <a href="/user.php?u=<?php echo urlencode($post['username']); ?>">@<?php echo h($post['username']); ?></a>
        · <span><?php echo h(time_ago($post['created_at'])); ?></span>
      </div>
      <div class="content"><?php echo nl2br(h($post['content'])); ?></div>
    </article>
  <?php endforeach; endif; ?>
</section>

<?php include __DIR__ . '/partials_footer.php'; ?>
