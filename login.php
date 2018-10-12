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
$user = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $_POST;
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

    $errors = array_merge($errors, validate_password($_POST['email'], $_POST['password'], $link));

    if (!count($errors)) {
        // $type = mime_content_type($_FILES['gif_img']['tmp_name']) === "image/png" ? '.png' : '.jpg';
        // $filename = uniqid() . $type;
        // move_uploaded_file($_FILES['gif_img']['tmp_name'], 'uploads/' . $filename);

		// $sql = "INSERT INTO users (name, email, password, avatar, contact) VALUES(?, ?, ?, $filename, ?)";
        // $stmt = db_get_prepare_stmt($link, $sql, [
        //     $user['name'], $user['email'], password_hash($user['password'], PASSWORD_DEFAULT), $user['message']
        // ]);
        // $res = mysqli_stmt_execute($stmt);

        // if ($res) {
        //     // $user_id = mysqli_insert_id($link);

        //     header("Location: login.php");
        //     exit();
        // }

        // if ($res) {
        //    $page_content = include_template('view.php', ['gif' => $gif]);
        // }  else {
        //     $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
        // }
	}
}

$page_content = include_template('login.php', [
    'categories' => $categories,
    'errors' => $errors,
    'user' => $user
]);

$layout_content = include_template('layout.php', [
	'content'    => $page_content,
	'categories' => $categories,
	'title'      => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar
]);

print($layout_content);