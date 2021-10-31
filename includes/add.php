<?php

include_once "config.php";
include_once "functions.php";
// cl_var_dump($_SESSION["user"]["id"], "_SESSION[\'id\']");

// Если ссылка и идентификатор авторизованного пользователя переданы:
if (isset($_POST['link']) && !empty($_POST['link']) && isset($_POST['user_id']) && !empty($_POST['user_id'])) {
    // Если удалось добавить ссылку в БД:
    if (add_link($_POST['user_id'], $_POST['link'])) {
        $_SESSION["success"] = "Ссылка успешно добавлена!";
    } else {
        $_SESSION["error"] = "Во время добавления ссылки что-то пошло не так!";
    }
}

// cl_var_dump($_POST['user_id'], "user_id");
// cl_var_dump($_POST['link'], "_POST link");

// Переход на страницу профиля пользователя.
header('Location: ' . get_url("profile.php"));
die;
