<?php
// $is_auth = (bool) rand(0, 1);
date_default_timezone_set('Europe/Moscow');
// $user_name = 'Алексей'; // укажите здесь ваше имя

session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : [];

$user_avatar = 'img/user.jpg';
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