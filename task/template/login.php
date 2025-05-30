<h2 class="form-title">Вход</h2>

<?php
session_start();
if (!empty($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        echo "<p class='error-message'>" . htmlspecialchars($error) . "</p>";
    }
    unset($_SESSION['errors']);
}
?>
<link rel="stylesheet" href="/style/style.css">
<form action="index.php?route=loginUser" method="POST" class="auth-form">
    <input type="text" name="username" placeholder="Логин" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <button type="submit">Войти</button>
</form>

<p class="form-footer">Нет аккаунта? <a href="index.php?route=register">Зарегистрируйтесь</a></p>
