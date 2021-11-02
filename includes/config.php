<?php
// TODO Закомментировать отображение ошибок.
error_reporting(E_ALL && E_NOTICE);
ini_set('display_errors', 1);

define('SITE_NAME', 'Cut your URL');

define('HOST', $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"]);
// Вариант для тех, у кого проект в подпапке сервера:
// define('HOST', $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/cut-your-url');

define('DB_HOST', 'localhost');
define('DB_NAME', 'cut_url');
define('DB_USER', 'root');
define('DB_PASS', ''); // root

session_start();
