<?php

require_once '../config/db.php';
require '../vendor/autoload.php';


$token = $_COOKIE['auth_token'] ?? null;

if (!$token || !$redis->exists("session:$token")) {
    header('Location: /index.php?route=login');
    exit;
}

$userData = json_decode($redis->get("session:$token"), true);


function getAllTasksByID($pdo, $uid)
{
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE owner = :uid");
    $stmt->execute(['uid' => $uid]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function countAll($pdo, $uid)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE owner = :uid");
    $stmt->execute(['uid' => $uid]);
    return $stmt->fetchColumn();
}

function countAllDone($pdo, $uid)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE owner = :uid AND status = 'done'");
    $stmt->execute(['uid' => $uid]);
    return $stmt->fetchColumn();
}

function countAllInProcess($pdo, $uid)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE owner = :uid AND status = 'in_progress'");
    $stmt->execute(['uid' => $uid]);
    return $stmt->fetchColumn();
}

$tasks = getAllTasksByID($pdo, $userData['id']);
$allTasks = countAll($pdo, $userData['id']);
$doneTasks = countAllDone($pdo, $userData['id']);
$processTasks = countAllInProcess($pdo, $userData['id']);
?>

<h1 class="welcome">Добро пожаловать, <?php echo htmlspecialchars($userData['username']) ?>!</h1>
<p class="task-summary">Ваши задачи:</p>

<?php if (empty($tasks)): ?>
    <p class="no-tasks">У вас нет задач.</p>
<?php else: ?>
    <ul class="task-list">
        <?php foreach ($tasks as $task): ?>
            <li class="task-item">
                <div class="task-header">
                    <strong><?php echo htmlspecialchars($task['title']) ?></strong>
                </div>
                <p class="task-desc"><?php echo htmlspecialchars($task['description']) ?></p>
                <p class="task-status">Статус: <span><?php echo htmlspecialchars($task['status']) ?></span></p>
                <p class="task-deadline">Выполнить до: <span><?php echo htmlspecialchars($task['deadline']) ?></span></p>

                <form action="index.php?route=deleteTask" method="POST">
                    <input type="hidden" name="task_id" value="<?php echo $task['id'] ?>">
                    <button type="submit" class="delete-btn" onclick="return confirm('Удалить эту задачу?')">Удалить</button>
                </form>
                
                <a class="edit-link" href="index.php?route=editTask&id=<?php echo $task['id'] ?>">Редактировать</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<div class="task-stats">
    <p>Всего задач: <strong><?php echo $allTasks ?></strong></p>
    <p>Выполнено задач: <strong><?php echo $doneTasks ?></strong></p>
    <p>В стадии выполнения задач: <strong><?php echo $processTasks ?></strong></p>
</div>

