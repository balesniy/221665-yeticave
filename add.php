<?php
$title = 'Новый лот';
require_once 'init.php';

$errors = [];
$lot = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($user)) {
        header("HTTP/1.0 403 Forbidden");
        print("403 Анонимный пользователь не может добавлять лот");
        exit();
    }


    $lot = $_POST;

    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $numbers = ['lot-rate', 'lot-step'];
    $dates = ['lot-date'];
    $maxLength = [
        'lot-name' => 128
    ];
    
    foreach ($required as $key) {
		if (empty(trim($_POST[$key]))) {
            $errors[$key] = 'Это поле надо заполнить';
		}
    }

    foreach ($numbers as $key) {
		if (empty($errors[$key])) {
            $errors = array_merge($errors, validate_number($_POST[$key], $key));
		}
    }

    foreach ($dates as $key) {
		if (empty($errors[$key])) {
            $errors = array_merge($errors, validate_date($_POST[$key], $key));
		}
    }

    foreach ($maxLength as $key => $value) {
		if (empty($errors[$key]) && strlen($_POST[$key]) > $value) {
            $errors[$key] = "Введите не больше $value знаков";
		}
    }
    
    $errors = array_merge($errors, validate_category($_POST['category'], $link), validate_img('gif_img'));
    
    if (!count($errors)) {
        $type = mime_content_type($_FILES['gif_img']['tmp_name']) === "image/png" ? '.png' : '.jpg';
        $filename = uniqid() . $type;
        move_uploaded_file($_FILES['gif_img']['tmp_name'], 'img/' . $filename);
		$sql = "INSERT INTO lots (name, description, category_id, start_amount, amount_step, img, user_id, finish) VALUES(?, ?, ?, ?, ?, $filename, 1, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [
            $lot['lot-name'], $lot['message'], $lot['category'], $lot['lot-rate'], $lot['lot-step'], $lot['lot-date']
        ]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);

            header("Location: lot.php?id=" . $lot_id);
        }

	}

} 

$page_content = include_template('add.php', [
    'categories' => $categories,
    'errors' => $errors,
    'lot' => $lot
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