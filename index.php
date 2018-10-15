<?php
$title = 'Главная';

require_once 'init.php';
 

$sql = 'SELECT lots.id, finish, name as title, start_amount as price, img as picture, categories.title as category
FROM lots
JOIN categories on category_id=categories.id
WHERE finish > NOW()
ORDER BY reg_date DESC';

if(isset($_GET['category'])){
    $category = intval($_GET['category']);
    $sql = "SELECT lots.id, finish, name as title, start_amount as price, img as picture, categories.title as category
    FROM lots
    JOIN categories on category_id=categories.id
    WHERE finish > NOW() AND category_id=$category
    ORDER BY reg_date DESC";
}

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
