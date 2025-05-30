<?php


require_once '../config/db.php';


$route = $_GET['route'] ?? 'index';


$template = '../template/layout.php';

switch ($route) {
    case 'index':
        $content = '../template/main.php';
        break;
    case 'addTask':
        $content = '../template/addTask.php';
        break;
    case 'login':
        $content = '../template/login.php';
        break;
    case 'register':
        $content = '../template/register.php';
        break;
    case 'loginUser':
        require_once '../handlers/login.php';
        break;
    case 'registerUser':
        require_once '../handlers/register.php';
        break;
    case 'logout':
        require_once '../handlers/logout.php';
        break;
    case 'addTaskHand':
        require_once '../handlers/addTask.php';
        break;
    case 'deleteTask':
        require_once '../handlers/deleteTask.php';
        break;
    case 'editTask':
        $content = '../template/editTask.php';
        break;
    case 'updateTask':
        require_once '../handlers/updateTask.php';
        break;
    default:
        http_response_code(404);
        $content = '../template/404.php';
        break;
}


// проверка содержится ли путь в массиве login register
// в данном случае если истинно он будет грузить только для логина или регистрации
if (in_array($route, ['login', 'register'])) {
    require_once $content;
} else {
    require_once $template;
}
?>
