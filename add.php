<?php
$title = 'Новый лот';
require_once 'init.php';

$sql = 'SELECT `title`, `promo_class` FROM categories';
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
    
    foreach ($required as $key) {
		if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
		}
    }
    
    foreach ($numbers as $key) {
		if (!empty($_POST[$key]) && !is_numeric($_POST[$key])) {
            $errors[$key] = 'Введите число';
		}
    }
    
    print_r($_FILES['gif_img']);

    if (isset($_FILES['gif_img']['name'])) {
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
    
    if (count($errors)) {
		$page_content = include_template('add.php', [
            'lot' => $lot,
            'categories' => $categories,
            'errors' => $errors,
            'dict' => $dict
            ]);
	} else {
        $sql = 'INSERT INTO gifs (dt_add, category_id, user_id, title, description, path) VALUES (NOW(), ?, 1, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($link, $sql, [$gif['category'], $gif['title'], $gif['description'], $gif['path']]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $gif_id = mysqli_insert_id($link);

            // header("Location: gif.php?id=" . $gif_id);

            $page_content = include_template('view.php', ['gif' => $gif]);

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