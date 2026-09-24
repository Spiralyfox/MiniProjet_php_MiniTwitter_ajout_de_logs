<?php
require_once __DIR__ . '/helpers.php';
require_login();
check_csrf();

$me = $_SESSION['user_id'];
$target = (int)($_POST['user_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($target > 0) {
    // Follow
    if ($action === 'follow') follow($me, $target);
    // Unfollow
    if ($action === 'unfollow') unfollow($me, $target);
}
$ref = $_SERVER['HTTP_REFERER'] ?? '/users.php';
header('Location: ' . $ref);
exit;