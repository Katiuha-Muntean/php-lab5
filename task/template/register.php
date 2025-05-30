<h2 class="form-title">Регистрация</h2>

<?php
session_start();

if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        echo "<p class='error-message'>" . htmlspecialchars($error) . "</p>";
    }
    unset($_SESSION['errors']);
}

if (isset($_SESSION['success'])) {
    echo "<p class='success-message'>" . htmlspecialchars($_SESSION['success']) . "</p>";
    unset($_SESSION['success']);
}
?>
<link rel="stylesheet" href="/style/style.css">
<form action="index.php?route=registerUser" method="POST" class="auth-form">
    <input type="text" name="username" placeholder="Логин" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <button type="submit">Зарегистрироваться</button>
</form>

<p class="form-footer">Уже есть аккаунт? <a href="index.php?route=login">Войдите</a></p>
