<?php
session_start();
require_once 'connect.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Квитанция</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
</head>

<body>

<p style="text-align: center">Плательщик: <?= $_SESSION['user']['full_name']; ?></p>
<p style="text-align: center">Дата формирования
    квитанции: <? echo $now = date('d.m.Y H:i', strtotime("+2 hours")); ?></p>
<div style="width: 400px; margin: 0 auto;">
    <form action="" method="POST">
        <input style="cursor: pointer" type="submit" name="submit" value="Оплатить">
    </form>
    <a href="/profile.php"><< назад</a>
</div>
<br>
<h3 style="text-align: center">ЗАО "БАНК", г. Усть-Катав</h3>
<p style="text-align: center">>> Банк получателя</p>
<h3 style="text-align: center">ООО "Компания"</h3>
<p style="text-align: center">>> Получатель</p>
<hr style="width: 20%; margin-left: 610px;">
<p style="text-align: center">ИНН 0000000000</p>
<p style="text-align: center">КПП 0000000000</p>
<p style="text-align: center">БИК 00000000</p>
<p style="text-align: center">Сч. № 00000000000000000000</p>
<br>
<?php
$submit = $_POST['submit'];
if (isset($submit)) {
    $general_id = $_SESSION['user']['id'];
    $check_user = mysqli_query($connect, "SELECT * FROM `tenants` WHERE `id_user` = '$general_id'");
    if (mysqli_num_rows($check_user) > 0) {
        $user = mysqli_fetch_assoc($check_user);
        $_SESSION['tenants'] = [
            "date_in" => $user['date_in'],
            "room" => $user['room']
        ];
        $result = $_SESSION['tenants']['date_in'];
        $now = time();
        $data_zaselenia = strtotime($result);
        $datediff = $now - $data_zaselenia;
        $itog = round($datediff / (60 * 60 * 24));
        $hol_water = 1 * $itog;
        $gor_water = 2 * $itog;
        $elektro = 3 * $itog;
        $clear = 4 * $itog;
        $naem = 5 * $itog;
        $oplata = $hol_water + $gor_water + $elektro + $naem + $clear;
        $sum = $oplata;
        $date_of_payment = date('d.m.Y H:i', time());
        $check_sum = mysqli_query($connect, "SELECT * FROM `sum`");
        mysqli_query($connect, "INSERT INTO `sum` (`id_user`, `date_of_payment`, `sum`) VALUES ('$general_id', '$date_of_payment', '$sum')");
        echo "<p style='text-align: center; color: red'>Оплата произведена</p>";
    }
}
?>
<div class="blc">
    <table border="1" width="700">
        <tr style="text-align: center; font-weight: bold">
            <td>№</td>
            <td>Наименование товара, работ, услуг</td>
            <td>Цена</td>
            <td>Ед. изм.</td>
            <td>Кол-во <br> суток</td>
            <td>Сумма</td>
        </tr>
        <tr style="text-align: center">
            <td>1</td>
            <td>Холодная вода</td>
            <td>1р</td>
            <td>сутки</td>
            <td><?php session_start();
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
                    $data_zaselenia = strtotime($result);
                    $itog_dz = strtotime("-2 hours", $data_zaselenia);
                    $now = time(); // текущее время (timestamp)
                    $result_i = $now - $itog_dz; // время между датами (timestamp)
                    $itog = floor($result_i / 86400); // кол-во дней между датами (timestamp)
                    $hol_water = 1 * $itog;
                    $gor_water = 1.5 * $itog;
                    $elektro = 2 * $itog;
                    $clear = 2.5 * $itog;
                    $naem = 3 * $itog;
                    $oplata = $hol_water + $gor_water + $elektro + $naem + $clear;
                    if ($itog > 0) {
                        echo $itog;
                    } else {
                        echo 0;
                    }
                } ?></td>
            <td><? if ($hol_water > 0) {
                    echo $hol_water . "р";
                } else {
                    echo 0 . "р";
                } ?></td>
        </tr>
        <tr style="text-align: center">
            <td>2</td>
            <td>Горячая вода</td>
            <td>1.5р</td>
            <td>сутки</td>
            <td><? if ($itog > 0) {
                    echo $itog;
                } else {
                    echo 0;
                } ?></td>
            <td><? if ($gor_water > 0) {
                    echo $gor_water . "р";
                } else {
                    echo 0 . "р";
                } ?></td>
        </tr>
        <tr style="text-align: center">
            <td>3</td>
            <td>Электроэнергия</td>
            <td>2р</td>
            <td>сутки</td>
            <td><? if ($itog > 0) {
                    echo $itog;
                } else {
                    echo 0;
                } ?></td>
            <td><? if ($elektro > 0) {
                    echo $elektro . "р";
                } else {
                    echo 0 . "р";
                } ?></td>
        </tr>
        <tr style="text-align: center">
            <td>4</td>
            <td>Уборка комнаты</td>
            <td>2.5р</td>
            <td>сутки</td>
            <td><? if ($itog > 0) {
                    echo $itog;
                } else {
                    echo 0;
                } ?></td>
            <td><? if ($clear > 0) {
                    echo $clear . "р";
                } else {
                    echo 0 . "р";
                } ?></td>
        </tr>
        <tr style="text-align: center">
            <td>5</td>
            <td>Плата за наём</td>
            <td>3р</td>
            <td>сутки</td>
            <td><? if ($itog > 0) {
                    echo $itog;
                } else {
                    echo 0;
                } ?></td>
            <td><? if ($naem > 0) {
                    echo $naem . "р";
                } else {
                    echo 0 . "р";
                } ?></td>
        </tr>
        <br>
        <tr style="text-align: center; font-weight: bold">
            <td colspan="5">Итого к оплате:</td>
            <td><? if ($oplata > 0) {
                    echo $oplata . "р";
                } else {
                    echo 0 . "р";
                } ?></td>
        </tr>
    </table>
</div>
<br>
<p style="margin-left: 410px;">Всего наименований 5, на сумму <? if ($oplata > 0) {
        echo $oplata . "р.";
    } else {
        echo 0 . "р.";
    } ?></p>
<hr color="#000" size="2" style="width: 46%; margin-left: 411px;">
<br>
<br>
<br>
<p style="margin-left: 410px;">Бухгалтер</p>
<img style="margin-left: 510px; margin-top: -105px" src="img/pod.png" width="180">
<p style="margin-left: 710px; margin-top: -128px"><u>Иванов Иван Иванович</u></p>
<br>
<div style=" margin-left: 930px; margin-top: -150px; " class="pct">
    <img src="img/pct.jpg" width="180">
</div>

</body>
</html>