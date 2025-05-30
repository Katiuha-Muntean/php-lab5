<?php

require_once '../config/db.php';

session_start(); 

$errors = [];  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $errors[] = 'Поля не должны быть пустые!';
    }

    if (strlen($username) < 3 || strlen($username) > 20) {
        $errors[] = 'Логин должен быть от 3 до 20 символов!';
    }

    if (strlen($password) < 6 || strlen($password) > 50) {
        $errors[] = 'Пароль должен быть от 6 до 50 символов!';
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $errors[] = 'Пользователь с таким именем уже существует';
    }

    if (empty($errors)) {
        $hashedPass = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPass]);

        $_SESSION['success'] = 'Регистрация прошла успешно! Теперь можете войти.';
        header('Location: /index.php?route=register');
        exit;
    }
}

$_SESSION['errors'] = $errors;

header('Location: /index.php?route=register');
exit;
