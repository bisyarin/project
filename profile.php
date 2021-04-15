<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
</head>

<body>

<div class="container">
    <form action="" method="POST">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <img src="<?= $_SESSION['user']['avatar'] ?>" width="150" style="border-radius: 10px" alt="">
        <h2 style="margin: 10px 0;">Пользователь: <?= $_SESSION['user']['full_name']; ?></h2>
        <hr>
        <h2 style="margin: 10px 0;">Дата заселения:
            <?
            session_start();
            require_once 'connect.php';
            $general_id = $_SESSION['user']['id'];
            $check_user = mysqli_query($connect, "SELECT * FROM `tenants` WHERE `id_user` = '$general_id'");
            if (mysqli_num_rows($check_user) > 0) {
                $user = mysqli_fetch_assoc($check_user);
                $_SESSION['tenants'] = [
                    "date_in" => $user['date_in'],
                    "room" => $user['room']
                ];
                $result = $_SESSION['tenants']['date_in'];
                echo $result . '<br>';
                $data_zaselenia = strtotime($result);
                $itog_dz = strtotime("-2 hours", $data_zaselenia);
                $now = time(); // текущее время (timestamp)
                $result_i = $now - $itog_dz; // время между датами (timestamp)
                $itog = floor($result_i / 86400); // кол-во дней между датами (timestamp)
                if ($itog > 0) {
                    echo "<p style='font-weight: normal'>Прожитых дней: " . $itog . "</p>";
                } else {
                    echo "<p style='font-weight: normal'>Прожитых дней: 0</p>";
                }
                $room = $_SESSION['tenants']['room'];
                echo "<p style='font-weight: normal'>Ваша комната: " . $room . "</p><hr>";
                $hol_water = 1 * $itog;
                $gor_water = 1.5 * $itog;
                $elektro = 2 * $itog;
                $clear = 2.5 * $itog;
                $naem = 3 * $itog;
                if ($hol_water > 0 and $gor_water > 0 and $elektro > 0 and $clear > 0 and $naem > 0) {
                    echo "Необходимо оплатить:";
                    echo "<p style='font-weight: normal'>Холодная вода (1р/сутки) >> " . "<u>" . $hol_water . "р</u>" . "</p>";
                    echo "<p style='font-weight: normal'>Горячая вода (1.5р/сутки) >> " . "<u>" . $gor_water . "р</u>" . "</p>";
                    echo "<p style='font-weight: normal'>Электроэнергия (2р/сутки) >> " . "<u>" . $elektro . "р</u>" . "</p>";
                    echo "<p style='font-weight: normal'>Уборка комнаты (2.5р/сутки) >> " . "<u>" . $clear . "р</u>" . "</p>";
                    echo "<p style='font-weight: normal'>Плата за наём (3р/сутки) >> " . "<u>" . $naem . "р</u>" . "</p>";
                    $oplata = $hol_water + $gor_water + $elektro + $naem + $clear;
                    echo "<p style='background-color: red; color: white'>Итого к оплате: " . $oplata . " руб. </p>";
                } else {
                    echo "Необходимо оплатить: 0р <br>";
                }
                echo "<a href='check.php'>Сформировать квитанцию</a>";
            } else {
                echo 'незаселённый(ая)';
            }
            ?>
        </h2>
        <a href="logout.php" class="logout">Выход</a>
        <?php
        session_start();
        require_once 'connect.php';
        $full_name = $_POST['full_name'];
        $room = $_POST['room'];
        $num_gr = $_POST['num_gr'];
        $code_st = $_POST['code_st'];
        $tel = $_POST['tel'];
        $id_user = $_SESSION['user']['id'];
        $general_id = $_SESSION['user']['id']; // получаем текущий id;
        $check_user = mysqli_query($connect, "SELECT * FROM `requests` WHERE `id_user` = '$general_id'");
        if (mysqli_num_rows($check_user) == 0) {
            echo "<h2>Создание заявки (учтите: заявка создаётся лишь 1 раз, будьте внимательны!)</h2>";
            $rst = $_SESSION['user']['full_name'];
            echo "<input name='full_name' type='text' value='$rst' placeholder='ФИО' required>";
            echo "Свободные комнаты: ";
            $check_room = mysqli_query($connect, "SELECT * FROM `rooms`");
            for ($data = []; $user = mysqli_fetch_assoc($check_room); $data[] = $user) ;
            $result = '';
            foreach ($data as $elem) {
                if ($elem['busy'] == 0) {
                    $result .= '<tr>';
                    $result .= '<td>' . $elem['room'] . ', </td> ';
                    $result .= '</tr>';
                }
            }
            echo $result;
            echo "<input name='room' type='text' placeholder='Комната'>";
            echo "<input name='num_gr' type='text' placeholder='Группа' required>";
            echo "<input name='code_st' type='text' placeholder='Код студенческого' required>";
            echo "<input name='tel' type='tel' placeholder='Телефон' required>";
            echo "<button name='sbt' type='submit'>Отправить заявку</button>";
            if (isset($_POST['sbt'])) {
                $check_code = mysqli_query($connect, "SELECT * FROM `requests` WHERE `code_st` = '$code_st'");
                if (mysqli_num_rows($check_code) == 0) {
                    mysqli_query($connect, "INSERT INTO `requests` (`id`, `id_user`, `full_name`, `room`, `num_gr`, `code_st`, `tel`) VALUES (NULL, '$id_user', '$full_name', '$room', '$num_gr', '$code_st', '$tel')");
                    $_SESSION['message'] = 'Заявка отправлена';
                } else {
                    $_SESSION['message'] = 'Заявка не отправлена, так как поле <Код студенческого> должно быть уникальным';
                }
            }
        } else {
            echo "<p style='color: yellow; background-color: green; font-weight: bold'>Ваша заявка была принята.</p>";
        }
        if ($_SESSION['message']) {
            echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
        }
        unset($_SESSION['message']);
        ?>
    </form>
    <div class="new_password">
        <table>
            <form action="" method="POST">
                <input name="new_password" type="password" placeholder="Новый пароль" required>
                <button name="n_p" type="submit">Изменить пароль</button>
                <?php
                session_start();
                require_once 'connect.php';
                $new_password = md5($_POST['new_password']);
                if (isset($_POST['n_p'])) {
                    $id = $_SESSION['user']['id'];
                    mysqli_query($connect, "UPDATE `users` SET `password` = '$new_password' WHERE `id` = '$id'");
                    $_SESSION['msg'] = 'Пароль изменён';
                }
                if ($_SESSION['msg']) {
                    echo '<p class="msg"> ' . $_SESSION['msg'] . ' </p>';
                }
                unset($_SESSION['msg']);
                ?>
            </form>
        </table>
    </div>
</div>

</body>
</html>