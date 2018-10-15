<?php
$title = 'Лот';
require_once 'init.php';
 

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

$user_id = $user['id'];

if($lot['user_id'] == $user_id){
    
    $user['error'] = 'это ваш лот';
}


$sql = "SELECT * FROM bets JOIN users ON user_id=users.id WHERE lot_id=$id ORDER BY reg_date DESC";

$result = mysqli_query($link, $sql);
if (!$result){
    $error = mysqli_error($link);
    show_error($error);
}

$bets = mysqli_fetch_all($result, MYSQLI_ASSOC);

$user_id = $user['id'];

        $sql = "SELECT *
        FROM bets
        WHERE lots_id=$id AND user_id=$user_id";

        $result = mysqli_query($link, $sql);
        if (!$result){
            $error = mysqli_error($link);
            show_error($error);
        }
        if (mysqli_num_rows($result)) {
            $user['error'] = 'ставка уже сделана';
        }


$content = include_template('lot.php', [
    'categories' => $categories,
    'lot' => $lot,
    'bets' => $bets,
    'user' => $user
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user['name'],
    'is_auth' => count($user),
    'user_avatar' => $user['avatar']
]);

print($layout);
?>