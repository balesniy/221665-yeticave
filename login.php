<?php
$title = 'Вход';
require_once 'init.php';

$sql = 'SELECT `title`, `promo_class`, `id` FROM categories';
$result = mysqli_query($link, $sql);
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($link);
    show_error($error);
}

$errors = [];
$login = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $login = $_POST;
    $required = ['email', 'password'];

    $maxLength = [
        'email' => 128,
        'password' => 64
    ];

    foreach ($required as $key) {
		if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
		}
    }

    foreach ($maxLength as $key => $value) {
		if (empty($errors[$key]) && strlen($_POST[$key]) > $value) {
            $errors[$key] = "Введите не больше $value знаков";
		}
    }

    $valid_user = validate_password($_POST['email'], $_POST['password'], $link);

    if(!isset($valid_user['user'])){
        $errors = array_merge($errors, $valid_user);
    }
    
    if (!count($errors)) {
        session_start();
        $_SESSION['user'] = $valid_user['user'];
        header("Location: index.php");

	}
}

$page_content = include_template('login.php', [
    'categories' => $categories,
    'errors' => $errors,
    'login' => $login
]);

$layout_content = include_template('layout.php', [
	'content'    => $page_content,
	'categories' => $categories,
	'title'      => $title,
    'user_name' => $user['name'],
    'is_auth' => count($user),
    'user_avatar' => $user['avatar']
]);

print($layout_content);