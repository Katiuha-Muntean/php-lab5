<?php
require_once '../config/db.php';
require '../vendor/autoload.php';

$token = $_COOKIE['auth_token'] ?? null;

if (!$token || !$redis->exists("session:$token")) {
    header('Location: /index.php?route=login');
    exit;
}

$userData = json_decode($redis->get("session:$token"), true);

$taskId = $_GET['id'] ?? null;
if (!$taskId) {
    header('Location: /index.php?route=index');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id AND owner = :owner");
$stmt->execute([
    'id' => $taskId,
    'owner' => $userData['id']
]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    echo "Задача не найдена.";
    exit;
}
?>

<h2>Редактировать задачу</h2>
<form action="index.php?route=updateTask" method="POST">
    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required><br><br>
    <textarea name="description" required><?= htmlspecialchars($task['description']) ?></textarea><br><br>
    <select name="status">
        <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : '' ?>>Ожидает</option>
        <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>В процессе</option>
        <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>Выполнено</option>
    </select><br><br>
    <input type="date" name="deadline" value="<?= $task['deadline'] ?>"><br><br>
    <button type="submit">Сохранить</button>
</form>
