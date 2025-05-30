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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['task_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);
    $deadline = trim($_POST['deadline']);

    $stmt = $pdo->prepare("UPDATE tasks SET title = :title, description = :description, status = :status, deadline = :deadline WHERE id = :id");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'status' => $status,
        'deadline' => $deadline,
        'id' => $id
    ]);
}

header('Location: /index.php?route=index');
exit;
