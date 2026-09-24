<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/logger.php';

function current_user() {
    if (!empty($_SESSION['user_id'])) {
        return find_user_by_id($_SESSION['user_id']);
    }
    return null;
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}

function find_user_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT id, username, email, bio, avatar_url, created_at FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function find_user_by_username($username) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT id, username, email, bio, avatar_url, created_at FROM users WHERE username = ?');
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function h($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function is_following($follower_id, $followee_id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT 1 FROM follows WHERE follower_id = ? AND followee_id = ?');
    $stmt->execute([$follower_id, $followee_id]);
    return (bool)$stmt->fetchColumn();
}

function follow($follower_id, $followee_id) {
    global $pdo;
    if ($follower_id == $followee_id) return;
    $stmt = $pdo->prepare('INSERT IGNORE INTO follows (follower_id, followee_id) VALUES (?, ?)');
    $stmt->execute([$follower_id, $followee_id]);

    $ip = $_SERVER['REMOTE_ADDR'];
    write_log("INFO", $ip, $follower_id, "S'est mis à suivre l'utilisateur ID : ".$followee_id);

}

function unfollow($follower_id, $followee_id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM follows WHERE follower_id = ? AND followee_id = ?');
    $stmt->execute([$follower_id, $followee_id]);

    $ip = $_SERVER['REMOTE_ADDR'];
    write_log("INFO", $ip, $follower_id, "S'est mis à ne plus suivre l'utilisateur ID : ".$followee_id);

}

function time_ago($datetime) {
    $ts = strtotime($datetime);
    $diff = time() - $ts;
    if ($diff < 60) return $diff . 's';
    if ($diff < 3600) return floor($diff/60) . 'm';
    if ($diff < 86400) return floor($diff/3600) . 'h';
    return date('Y-m-d', $ts);
}
?>
