<?php
$is_auth = (bool) rand(0, 1);
date_default_timezone_set('Europe/Moscow');
$user_name = 'Алексей'; // укажите здесь ваше имя
$title = 'Главная';
$user_avatar = 'img/user.jpg';
$categories = [];
$content = '';

require_once 'functions.php';
$link = mysqli_connect("localhost", "root", "root", "221665-yeticave");
 
if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
} else {
    $sql = 'SELECT `title`, `promo_class` FROM categories';
    $result = mysqli_query($link, $sql);
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }

    $sql = 'SELECT finish, name as title, start_amount as price, img as picture, categories.title as category
    FROM lots
    JOIN categories on category_id=categories.id
    WHERE finish > NOW()
    ORDER BY reg_date DESC';

    $result = mysqli_query($link, $sql);
    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $content = include_template('index.php', [
            'categories' => $categories,
            'lots' => $lots
        ]);
    } else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }
}

$layout = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar
]);

print($layout);
?>
