<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

$dsn = $config['root'] . DIRECTORY_SEPARATOR .  $config['dsn'];
$driver = $config['driver'];

try {
    $pdo = new PDO($driver . $dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(20) NOT NULL UNIQUE,
        password VARCHAR(50) NOT NULL
        )");


    $redis = new Predis\Client();
    $redis->connect('127.0.0.1', 6379);
} catch (PDOException $e) {
    die("Ошибка при подключении! ожидайте!");
}
