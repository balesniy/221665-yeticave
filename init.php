<?php
$is_auth = (bool) rand(0, 1);
date_default_timezone_set('Europe/Moscow');
$user_name = 'Алексей'; // укажите здесь ваше имя

$user_avatar = 'img/user.jpg';
$categories = [];
$content = '';

require_once 'functions.php';
$link = mysqli_connect("localhost", "root", "root", "221665-yeticave");
?>