<?php

include_once "config.php";
include_once "functions.php";

/*
    TODO Ниже нужно проверить,
    чтобы ссылка принадлежала именно авторизованному пользователю.
*/

// Если идентификатор пользователя и идентификатор ссылки не переданы:
if (!isset($_SESSION["user"]['id']) || empty($_SESSION["user"]['id']) || !isset($_GET['id']) || empty($_GET['id'])) {
    // Переход на страницу профиля пользователя.
    header('Location: ' . get_url("profile.php"));
    die;
}

// Удаляем ссылку с переданным идентификатором.
delete_link($_GET['id']);
$_SESSION["success"] = "Ссылка успешно удалена!";
// Переход на страницу регистрации.
header('Location: ' . get_url("profile.php"));
die;
