<?php
$title = 'Лот';
require_once 'init.php';
 
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
    $id = intval($_GET['id']);

    $sql = "SELECT *, (select max(amount) from bets where lot_id=lots.id) as price
    FROM lots
    JOIN categories on category_id=categories.id
    WHERE lots.id=$id";

    $result = mysqli_query($link, $sql);
    if ($result) {
        if (!mysqli_num_rows($result)) {
            http_response_code(404);
            $content = include_template('error.php', ['error' => 'Лот с этим идентификатором не найден']);
        } else {
            $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $content = include_template('lot.php', [
                'categories' => $categories,
                'lot' => $lot
            ]);
        }
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