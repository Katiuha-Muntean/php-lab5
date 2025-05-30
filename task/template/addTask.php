<?php

require_once '../config/db.php';
require '../vendor/autoload.php';

$token = $_COOKIE['auth_token'] ?? null;


if (!$token || !$redis->exists("session:$token")) {
    header('Location: /index.php?route=login');
    exit;
}

session_start();
if (!empty($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        echo "<p style='color: red;'>".htmlspecialchars($error)."</p>";
    }
    unset($_SESSION['errors']);
}

?>


<h2>Добавить задачу</h2>
<form action="index.php?route=addTaskHand" method="POST">
    <input type="text" name="title" placeholder="Название задачи" required><br><br>

    <textarea name="description" placeholder="Описание задачи" required></textarea><br><br>

    <select name="status">
        <option value="pending">Ожидает</option>
        <option value="in_progress">В процессе</option>
        <option value="done">Выполнено</option>
    </select><br><br>

    <input type="date" name="deadline"><br><br>

    <button type="submit">Добавить задачу</button>
</form>

