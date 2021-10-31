<?php
include_once "config.php";

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

/*
    Функция возвращает результат принятого запроса к БД.
    Если 2-ым параметром передано true, то выполняет принятый запрос к БД.
*/
function db_query($sql = '', $exec = false) {
    if (empty($sql)) {
        return false;
    }

    if ($exec) {
        return db()->exec($sql);
    }

    return db()->query($sql);
}

// Функция выполняет принятый запрос к БД.
// function db_exec($sql = '') {
//     if (empty($sql)) {
//         return false;
//     }

//     return db()->exec($sql);
// }

// Функция возвращает количество пользователей сайта.
function get_users_count() {
    return db_query("SELECT COUNT(id) FROM `users`;")->fetchColumn();
}

// Функция возвращает количество ссылок в системе.
function get_links_count() {
    return db_query("SELECT COUNT(`id`) FROM `links`;")->fetchColumn();
}

// Функция возвращает количество переходов по ссылкам.
function get_views_count() {
    return db_query("SELECT SUM(`views`) FROM `links`;")->fetchColumn();
}

// Функция возвращает информацию о ссылке, соответствующей введённой пользователем.
function get_link_info($url) {
    if (empty($url)) {
        return [];
    }

    return db_query("SELECT * FROM `links` WHERE `short_link` = '$url';")->fetch();
}

// Функция возвращает информацию о переданном пользователе.
function get_user_info($login) {
    if (empty($login)) {
        return [];
    }

    return db_query("SELECT * FROM `users` WHERE `login` = '$login';")->fetch();
}

// Функция обновляет количество переходов по ссылкам.
function update_views($url) {
    if (empty($url)) {
        return false;
    }

    return db_query("UPDATE `links` SET `views` = `views` + 1 WHERE `links`.`short_link` = '$url';", true);
}

/*
    Функция добавляет нового пользователя в БД. Возвращает количество
    добавленных строк. Если удалось добавить пользователя, возвращает 1.
*/
function add_user($login, $pass) {
    $password = password_hash($pass, PASSWORD_DEFAULT);

    return db_query("INSERT INTO `users` (`id`, `login`, `pass`) VALUES (NULL, '$login', '$password');", true);
}

// Функция регистрирует нового пользователя.
function register_user($auth_data) {
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) || !isset($auth_data['pass']) || !isset($auth_data['pass2'])) {
        return false;
    }

    $user = get_user_info($auth_data['login']);
    // Если на сайте уже есть переданный пользователь:
    if (!empty($user)) {
        $_SESSION["error"] = "Пользователь '" . $auth_data['login'] . "' уже существует.";
        // Переход на страницу регистрации.
        header('Location: ' . get_url("register.php"));
        die;
    }

    // Если пароли не совпадают:
    if ($auth_data['pass'] !== $auth_data['pass2']) {
        $_SESSION["error"] = "Пароли не совпадают.";
        // Переход на страницу регистрации.
        header('Location: ' . get_url("register.php"));
        die;
    }

    // Если новый пользователь успешно добавлен в БД:
    if (add_user($auth_data['login'], $auth_data['pass'])) {
        $_SESSION["success"] = "Регистрация прошла успешно.";
        // Переход на страницу регистрации.
        header('Location: ' . get_url("login.php"));
        die;
    }

    return true;
}

// Функция авторизует пользователя.
function login($auth_data) {
    // Если нет учётных данных пользователя или они неполные:
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) || !isset($auth_data['pass']) || empty($auth_data['pass'])) {
        $_SESSION["error"] = "Логин или пароль не может быть пустым.";
        // Переход на страницу регистрации.
        header('Location: ' . get_url("login.php"));
        die;
    }

    $user = get_user_info($auth_data['login']);
    // Если на сайте нет переданного пользователя:
    if (empty($user)) {
        $_SESSION["error"] = "Логин или пароль неверен!";
        // Переход на страницу регистрации.
        header('Location: ' . get_url("login.php"));
        die;
    }

    // Если пароль пользователя верен:
    if (password_verify($auth_data['pass'], $user['pass'])) {
        // Записать информацию об авторизации в сессию.
        $_SESSION["user"] = $user;
        // Переход на страницу профиля пользователя.
        header('Location: ' . get_url("profile.php"));
        die;

        // Если пароль пользователя неверен:
    } else {
        // Записать информацию об авторизации в сессию.
        $_SESSION["error"] = "Пароль неверен!";
        // Переход на страницу профиля пользователя.
        header('Location: ' . get_url("login.php"));
        die;
    }
}

// Функция разлогинивает пользователя сайта.
function logout() {
    session_destroy();
    header('Location: ' . HOST);
}

// Функция возвращает ссылки переданного пользователя.
function get_user_links($user_id) {
    if (empty($user_id)) {
        return [];
    }

    return db_query("SELECT * FROM `links` WHERE `user_id` = $user_id;")->fetchAll();
}

/*
    Функция удаляет ссылку с переданным идентификатором.
    Если ссылку удалить не удалось, возвращает false.
*/
function delete_link($id) {
    if (empty($id)) {
        return false;
    }

    return db_query("DELETE FROM `links` WHERE `links`.`id` = $id;", true);
}

/*
    Функция добавляет ссылку в БД.
    Принимает идентификатор авторизованного пользователя и ссылку.
*/
function add_link($user_id, $link) {
    $short_link = get_short_link();

    return db_query("INSERT INTO `links` (`id`, `user_id`, `long_link`, `short_link`, `views`) VALUES (NULL, '$user_id', '$link', '$short_link', '0') ;", true);
}

// Функция возвращает короткую ссылку.
function get_short_link($size = 6) {
    $alphabet = "abcdefghijklmnopqrstuvwxyz1234567890-";
    return substr(str_shuffle($alphabet), 0, $size);
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
