<?php
session_start();
require_once 'connect.php';
$full_name = $_POST['full_name'];
$login = $_POST['login'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$error_fields = [];
if ($full_name === '') {
    $error_fields[] = 'full_name';
}
if ($login === '') {
    $error_fields[] = 'login';
}
if ($password === '') {
    $error_fields[] = 'password';
}
if ($confirm_password === '') {
    $error_fields[] = 'confirm_password';
}
if (!$_FILES['avatar']) {
    $error_fields[] = 'avatar';
}
if (!empty($error_fields)) {
    $response = [
        "status" => false,
        "type" => 1,
        "message" => "Проверьте правильность полей",
        "fields" => $error_fields
    ];
    echo json_encode($response);
    die();
}
$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login'");
if (mysqli_num_rows($check_user) == 0) {
    if ($password === $confirm_password) {
        $path = 'uploads/' . time() . $_FILES['avatar']['name'];
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $path)) {
            $response = [
                "status" => false,
                "type" => 2,
                "message" => "Ошибка при загрузке аватарки"
            ];
            echo json_encode($response);
        }
        $password = md5($password);
        mysqli_query($connect, "INSERT INTO `users` (`id`, `full_name`, `login`, `password`, `avatar`) VALUES (NULL, '$full_name', '$login', '$password', '$path')");
        $response = [
            "status" => true,
            "message" => "Регистрация прошла успешно"
        ];
        echo json_encode($response);
    } else {
        $response = [
            "status" => false,
            "message" => "Пароли не совпадают"
        ];
        echo json_encode($response);
    }
} else {
    $response = [
        "status" => false,
        "message" => "Такой пользователь существует"
    ];
}
?>