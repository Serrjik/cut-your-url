<?php
include "./includes/config.php";

function get_url($page = '') {
    return HOST . "/$page";
}

// Функция создаёт подключение к БД.
function db() {
    try {
        return new PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

// Функция возвращает результат принятого запроса к БД.
function db_query($sql = '') {
    if (empty($sql)) {
        return false;
    }

    return db()->query($sql);
}

// Функция выполняет принятый запрос к БД.
function db_exec($sql = '') {
    if (empty($sql)) {
        return false;
    }

    return db()->exec($sql);
}

// Функции распечатки в консоль.
function cl_print_r($var, $label = '') {
    $str = json_encode(print_r($var, true));
    echo "<script>console.group('" . $label . "');console.log('" . $str . "');console.groupEnd();</script>";
}

function cl_var_dump($var, $label = '') {
    ob_start();
    var_dump($var);
    $result = json_encode(ob_get_clean());
    echo "<script>console.group('" . $label . "');console.log('" . $result . "');console.groupEnd();</script>";
}
