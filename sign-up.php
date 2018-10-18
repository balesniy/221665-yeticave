<?php
$title = 'Регистрация';
require_once 'init.php';

if (!empty($user)) {
    header("Location: index.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $_POST;
    $required = ['email', 'password', 'name', 'message'];

    $maxLength = [
        'name' => 64,
        'email' => 128,
        'password' => 64
    ];

    foreach ($required as $key) {
		if (empty(trim($_POST[$key]))) {
            $errors[$key] = 'Это поле надо заполнить';
		}
    }

    foreach ($maxLength as $key => $value) {
		if (empty($errors[$key]) && strlen($_POST[$key]) > $value) {
            $errors[$key] = "Введите не больше $value знаков";
		}
    }

    $errors = array_merge($errors, validate_email($_POST['email'], $link), validate_img('gif_img', false));

    if(is_uploaded_file($_FILES['gif_img']['tmp_name']) && !count($errors)) {
        $type = mime_content_type($_FILES['gif_img']['tmp_name']) === "image/png" ? '.png' : '.jpg';
        $filename = uniqid() . $type;
        move_uploaded_file($_FILES['gif_img']['tmp_name'], 'img/' . $filename);
    } else {
        $filename = 'user.jpg';
    }

    if (!count($errors)) {
		$sql = "INSERT INTO users (name, email, password, avatar, contact) VALUES(?, ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [
            $user['name'], $user['email'], password_hash($user['password'], PASSWORD_DEFAULT), $filename, $user['message']
        ]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {

            header("Location: login.php");
            exit();
        }
	} 
}

$page_content = include_template('sign-up.php', [
    'categories' => $categories,
    'errors' => $errors,
    'user' => $user
]);

$layout_content = include_template('layout.php', [
	'content'    => $page_content,
	'categories' => $categories,
	'title'      => $title,
    'user_name' => '',
    'is_auth' => false,
    'user_avatar' => ''
]);

print($layout_content);