<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/logger.php';

session_destroy();
header('Location: /');
    $ip = $_SERVER['REMOTE_ADDR'];
    $user = current_user();
    write_log("INFO", $ip, $user['username'], "S'est déconnecté");
exit;
