<?php

date_default_timezone_set('Europe/Moscow');

session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : [];

$categories = [];
$content = '';

require_once 'functions.php';
$link = mysqli_connect("localhost", "root", "root", "221665-yeticave");
if (!$link) {
    $error = mysqli_connect_error();
    show_error($error);
}


$sql = 'SELECT `title`, `promo_class`, `id` FROM categories';
$result = mysqli_query($link, $sql);
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($link);
    show_error($error);
}


?>