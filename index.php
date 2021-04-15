<?php
session_start();
if ($_SESSION['user']) {
    header('Location: /profile.php');
}
if ($_SESSION['admin']) {
    header('Location: /admin.php');
}
if ($_SESSION['manager']) {
    header('Location: /manager.php');
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
</head>

<body>

<br>
<br>
<br>
<div class="container">
    <form>
        <input type="text" name="login" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit" class="login-btn">Авторизоваться</button>
        <p>
            У Вас нет аккаунта? - <a href="/register.php">Зарегистрируйтесь</a>
        </p>
        <p class="msg none">Тест</p>
    </form>
</div>
<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/main.js"></script>

</body>

</html>