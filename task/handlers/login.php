<?php
require_once '../config/db.php';
require '../vendor/autoload.php';

session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $errors[] = 'Поля не должны быть пустыми!';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Неверный логин или пароль';
        }
    }

    if (empty($errors)) {
        $token = bin2hex(random_bytes(8));

        $redis->setex("session:$token", 3600, json_encode([
            'id' => $user['id'],
            'username' => $user['username']
        ]));

        setcookie('auth_token', $token, time() + 3600, '/');
        header('Location: ../public/index.php');
        exit;
    }

    $_SESSION['errors'] = $errors;
    header('Location: ../public/index.php?route=login');
    exit;
} else {
    $_SESSION['errors'] = ['Метод не поддерживается'];
    header('Location: ../public/index.php?route=login');
    exit;
}
