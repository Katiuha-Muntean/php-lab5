<?php
require_once '../config/db.php';
require '../vendor/autoload.php';

$token = $_COOKIE['auth_token'] ?? null;

if (!$token || !$redis->exists("session:$token")) {
    header('Location: /index.php?route=login');
    exit;
}

$userData = json_decode($redis->get("session:$token"), true);
$userId = $userData['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $taskId = (int)$_POST['task_id'];

    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id AND owner = :owner");
    $stmt->execute([
        'id' => $taskId,
        'owner' => $userId
    ]);
}

header('Location: /index.php?route=index');
exit;
