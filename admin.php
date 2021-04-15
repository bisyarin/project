<?php
session_start();
if (!$_SESSION['admin']) {
    header('Location: /');
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
</head>

<body>

<div class="admin-container">
    <h2 style="margin: 10px 0;">Вход выполнен как: <?= $_SESSION['admin']['full_name'] ?> --> <a href="logout_admin.php"
                                                                                                 class="logout2">Выход</a>
    </h2>
    <br>
    <?php
    require_once 'connect.php';
    if (isset($_POST['sbt_remove'])) {
        $id_remove = $_POST['id_remove'];
        $role_remove = $_POST['role_remove'];
        mysqli_query($connect, "UPDATE `users` SET `role` = '$role_remove' WHERE `id` = '$id_remove'");
    }
    ?>
    <h1>Зарегистрированные пользователи:</h1>
    <table border="1" style="text-align: center" width="700">
        <tr>
            <th>ID</th>
            <th>Роль</th>
            <th>ФИО</th>
            <th>Логин</th>
        </tr>
        <?php
        $check_user = mysqli_query($connect, "SELECT * FROM `users`");
        for ($data = []; $user = mysqli_fetch_assoc($check_user); $data[] = $user) ;
        $result = '';
        foreach ($data as $elem) {
            $result .= '<tr>';
            $result .= '<td>' . $elem['id'] . ' </td> ';
            $result .= '<td>' . $elem['role'] . ' </td> ';
            $result .= '<td>' . $elem['full_name'] . ' </td> ';
            $result .= '<td>' . $elem['login'] . '</td>';
            $result .= '</tr>';
        }
        echo $result;
        ?>
    </table>
    <br>
    <h1>Редактировать роль: <br>(0 - Пользователь, 1 - Администратор, 2 - Менеджер)</h1>
    <form action="" method="POST">
        <input type="text" name="id_remove" placeholder="ID" required>
        <input type="text" name="role_remove" placeholder="Роль" required>
        <button name="sbt_remove" type="submit">Изменить</button>
    </form>
</div>

</body>
</html>