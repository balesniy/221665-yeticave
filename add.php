<?php
$title = 'Новый лот';
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;

    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $dict = ['title' => 'Название', 'description' => 'Описание', 'file' => 'Фото'];
    $numbers = ['lot-rate', 'lot-step'];
    $dates = ['lot-date'];
    $maxLength = [
        'lot-name' => 128
    ];
    
    foreach ($required as $key) {
		if (empty($_POST[$key])) {
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
    
    // print_r(var_dump($_FILES['gif_img']));

    if (is_uploaded_file($_FILES['gif_img']['tmp_name'])) {
		$tmp_name = $_FILES['gif_img']['tmp_name'];
		$path = $_FILES['gif_img']['name'];

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        
        if ($file_type !== "image/gif") {
			$errors['file'] = 'Загрузите картинку в формате GIF';
		} else {
			move_uploaded_file($tmp_name, 'uploads/' . $path);
            $gif['path'] = $path;
            
            // $filename = uniqid() . '.gif';
            // $gif['path'] = $filename;
            // move_uploaded_file($_FILES['gif_img']['tmp_name'], 'uploads/' . $filename);
		}
	} else {
		$errors['file'] = 'Вы не загрузили файл';
    }

    if (isset($_POST['category'])) {
        $errors = array_merge($errors, validate_category($_POST[$key], $key));
    }
    
    if (count($errors)) {
		$page_content = include_template('add.php', [
            'lot' => $lot,
            'categories' => $categories,
            'errors' => $errors,
            'dict' => $dict
            ]);
	} else {
        
        $sql = "INSERT INTO lots (name, description, category_id, start_amount, amount_step, img, user_id, finish) VALUES(?, ?, ?, ?, ?, 'img/lot-1.jpg', 1, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [
            $lot['lot-name'], $lot['message'], $lot['category'], $lot['lot-rate'], $lot['lot-step'], $lot['lot-date']
        ]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);

            header("Location: lot.php?id=" . $lot_id);

            // $page_content = include_template('view.php', ['gif' => $gif]);

        }  else {
            $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
        }

	}

} else {
	$page_content = include_template('add.php', [
        'categories' => $categories,
        'errors' => $errors,
        'lot' => []
    ]);
}

$layout_content = include_template('layout.php', [
	'content'    => $page_content,
	'categories' => $categories,
	'title'      => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar
]);

print($layout_content);