<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/style.css">
    <title>Список задач</title>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="index.php?route=index" class="nav-link">Главная</a>
            <a href="index.php?route=addTask" class="nav-link">Добавить задачу</a>
            <a href="index.php?route=logout" class="nav-link exit">Выйти</a>
        </nav>
    </header>
    <main class="main-content">
        <?php
        if (file_exists($content)) {
            require_once $content;
        } else {
            echo "<p>Ошибка: файл шаблона не найден.</p>";
        }
        ?>
    </main>
</body>
</html>
