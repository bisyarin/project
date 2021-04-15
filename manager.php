<?php
session_start();
if (!$_SESSION['manager']) {
    header('Location: /');
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Менеджер-панель</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
</head>

<body>

<div class="admin-container">
    <h2 style="margin: 10px 0;">Вход выполнен как: <?= $_SESSION['manager']['full_name'] ?> --> <a
                href="logout_manager.php"
                class="logout2">Выход</a>
    </h2>
    <h1>Заявки в арендаторы:</h1>
    <table border="1" style="text-align: center" width="700">
        <tr>
            <th>ID</th>
            <th>ФИО</th>
            <th>Комната</th>
            <th>Группа</th>
            <th>Код студ.</th>
            <th>Телефон</th>
        </tr>
        <?php
        require_once 'connect.php';
        if (isset($_GET['delete'])) {
            $delete = $_GET['delete'];
            mysqli_query($connect, "DELETE FROM `requests` WHERE `id` = '$delete'");
        }
        $check_user = mysqli_query($connect, "SELECT * FROM `requests`");
        for ($data = []; $user = mysqli_fetch_assoc($check_user); $data[] = $user) ;
        $result = '';
        foreach ($data as $elem) {
            $result .= '<tr>';
            $result .= '<td>' . $elem['id_user'] . ' </td> ';
            $result .= '<td>' . $elem['full_name'] . ' </td> ';
            $result .= '<td>' . $elem['room'] . ' </td>';
            $result .= '<td>' . $elem['num_gr'] . ' </td> ';
            $result .= '<td>' . $elem['code_st'] . ' </td> ';
            $result .= '<td>' . $elem['tel'] . ' </td> ';
            $result .= '<td><a href="?delete=' . $elem['id'] . '">удалить</a></td>';
            $result .= '</tr>';
        }
        echo $result;
        ?>
    </table>
    <br>
    <h1>Свободные комнаты: <?
        require_once 'connect.php';
        $check_user = mysqli_query($connect, "SELECT * FROM `rooms`");
        for ($data = []; $user = mysqli_fetch_assoc($check_user); $data[] = $user) ;
        $result = '';
        foreach ($data as $elem) {
            if ($elem['busy'] == 0) {
                $result .= '<tr>';
                $result .= '<td>' . $elem['room'] . ', </td> ';
                $result .= '</tr>';
            }
        }
        echo $result;
        ?>
    </h1>
    <div class="form-admin">
        <h1>Добавить арендатора:</h1>
        <form action="" method="POST">
            <input type="text" name="id_us" placeholder="Айди заявочника" required>
            <input type="text" name="full_name" placeholder="ФИО" required>
            <input type="text" name="room" placeholder="Комната" required>
            <input type="text" name="num_gr" placeholder="Группа" required>
            <input type="text" name="code_st" placeholder="Код студенческого" required>
            <input type="text" name="tel" placeholder="Телефон" required>
            Дата заселения: <input type="datetime-local" name="date_in" required>
            <button name="sbt" type="submit">Добавить</button>
        </form>
        <?php
        require_once 'connect.php';
        if (isset($_POST['sbt'])) {
            $id_us = $_POST['id_us'];
            $full_name = $_POST['full_name'];
            $room = $_POST['room'];
            $num_gr = $_POST['num_gr'];
            $code_st = $_POST['code_st'];
            $tel = $_POST['tel'];
            $date_in = $_POST['date_in'];
            $di = date('d.m.Y H:i', strtotime($date_in));
            mysqli_query($connect, "INSERT INTO `tenants` (`id_request`, `id_user`, `full_name`, `room`, `num_gr`, `code_st`, `tel`, `date_in`) VALUES ('$id_us', '$id_us', '$full_name', '$room', '$num_gr', '$code_st', '$tel', '$di')");
            mysqli_query($connect, "UPDATE `rooms` SET `id_user` = '$id_us', `busy` = 1 WHERE `room` = '$room'");
        }
        ?>
    </div>
    <h1>Добавленные арендаторы:</h1>
    <table border="1" style="text-align: center" width="700">
        <tr>
            <th>ID</th>
            <th>ФИО</th>
            <th>Комната</th>
            <th>Группа</th>
            <th>Код студ.</th>
            <th>Телефон</th>
            <th>Дата заселения</th>
        </tr>
        <?php
        require_once 'connect.php';
        if (isset($_GET['del'])) {
            $del = $_GET['del'];
            mysqli_query($connect, "SELECT * FROM `rooms`");
            mysqli_query($connect, "UPDATE `rooms` SET `id_user` = NULL, `busy` = NULL WHERE `id_user` = '$del'");
            mysqli_query($connect, "SELECT * FROM `sum`");
            mysqli_query($connect, "DELETE FROM `sum` WHERE `id_user` = '$del'");
            mysqli_query($connect, "SELECT * FROM `tenants`");
            mysqli_query($connect, "DELETE FROM `tenants` WHERE `id_request` = '$del'");
        }
        $check_user = mysqli_query($connect, "SELECT * FROM `tenants`");
        for ($data = []; $user = mysqli_fetch_assoc($check_user); $data[] = $user) ;
        $result = '';
        foreach ($data as $elem) {
            $result .= '<tr>';
            $result .= '<td>' . $elem['id_user'] . ' </td> ';
            $result .= '<td>' . $elem['full_name'] . ' </td> ';
            $result .= '<td>' . $elem['room'] . ' </td> ';
            $result .= '<td>' . $elem['num_gr'] . '</td>';
            $result .= '<td>' . $elem['code_st'] . ' </td> ';
            $result .= '<td>' . $elem['tel'] . ' </td> ';
            $result .= '<td>' . $elem['date_in'] . ' </td> ';
            $result .= '<td><a href="?del=' . $elem['id_request'] . '">удалить</a></td>';
            $result .= '</tr>';
        }
        echo $result;
        ?>
    </table>
</div>

</body>
</html>