<?php
require_once '../config/db.php';
require '../vendor/autoload.php';

session_start();

// Проверка авторизации через Redis
$token = $_COOKIE['auth_token'] ?? null;
if (!$token || !$redis->exists("session:$token")) {
    header('Location: /index.php?route=login');
    exit;
}

$user = json_decode($redis->get("session:$token"), true);
$ownerId = $user['id'];

$errors = [];

// Создание таблицы (можно убрать, если таблица уже есть)
$sql = "CREATE TABLE IF NOT EXISTS tasks(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    owner INTEGER,
    title VARCHAR(50) NOT NULL,
    description VARCHAR(250) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    deadline DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner) REFERENCES users(id) ON DELETE CASCADE
)";
$pdo->exec($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $status = trim($_POST['status']);
    $description = trim($_POST['description']);
    $deadline = trim($_POST['deadline']);

    if (empty($title) || empty($status) || empty($deadline)) {
        $errors[] = 'Поля должны быть заполнены';
    }

    if (strlen($title) < 3 || strlen($title) > 50) {
        $errors[] = 'Название должно иметь от 3 до 50 символов';
    }

    if (strlen($description) < 3 || strlen($description) > 250) {
        $errors[] = 'Описание должно иметь от 3 до 250 символов';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (owner, title, status, description, deadline) 
            VALUES (:owner, :title, :status, :description, :deadline)");
        $stmt->execute([
            'owner' => $ownerId,
            'title' => $title,
            'status' => $status,
            'description' => $description,
            'deadline' => $deadline
        ]);

        header('Location: /index.php?route=index');
        exit;
    }
}

$_SESSION['errors'] = $errors;
header('Location: /index.php?route=addTask');
exit;
