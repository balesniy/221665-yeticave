<?php
$title = 'Лот';
require_once 'init.php';
 

$sql = 'SELECT `title`, `promo_class` FROM categories';
$result = mysqli_query($link, $sql);
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($link);
    show_error($error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT *, categories.title as category_title,
GREATEST(COALESCE((select max(amount) from bets where lot_id=lots.id),0), start_amount) as price
FROM lots
JOIN categories on category_id=categories.id
WHERE lots.id=$id";

$result = mysqli_query($link, $sql);
if (!$result){
    $error = mysqli_error($link);
    show_error($error);
}
if (!mysqli_num_rows($result)) {
    http_response_code(404);
    show_error('Лот с этим идентификатором не найден');
}
$lot = mysqli_fetch_array($result, MYSQLI_ASSOC);
$content = include_template('lot.php', [
    'categories' => $categories,
    'lot' => $lot
]);

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