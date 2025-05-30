<?php

require_once '../config/db.php';
require_once '../vendor/autoload.php';

$token = $_COOKIE['auth_token'] ?? null;

if ($token) {
    $redis->del("session:$token");
    setcookie('auth_token', '', time() - 3600);
}

header('Location: /index.php?route=login');
exit;