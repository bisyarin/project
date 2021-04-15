<?php
session_start();
if ($_SESSION['user']) {
    header('Location: /profile.php');
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
        <input type="text" name="full_name" placeholder="ФИО" required>
        <input type="text" name="login" placeholder="Логин" required>
        <input type="file" name="avatar" placeholder="Аватарка">
        <input type="password" name="password" placeholder="Пароль" required>
        <input type="password" name="confirm_password" placeholder="Повторный пароль" required>
        <button type="submit" class="reg-btn">Зарегистрироваться</button>
        <p>
            Уже есть аккаунт? - <a href="/">Авторизируйтесь</a>
        </p>
        <p class="msg none">Тест</p>
    </form>
</div>
<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/main.js"></script>

</body>

</html>