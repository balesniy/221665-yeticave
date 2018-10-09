<?php
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function show_error($error){
    $content = include_template('error.php', ['error' => $error]);
    $layout = include_template('layout.php', [
        'content' => $content,
        'categories' => [],
        'title' => "Ошибка",
        'user_name' => '',
        'is_auth' => '',
        'user_avatar' => ''
    ]);
    print($layout);
    exit();
}

function price_format($price){
    $price = ceil($price);
    $rub_format = $price>999 ? number_format($price, 0, ".", " ") : $price;
    return "$rub_format ₽";
}

function get_time($finish_time){
    $current_time = date_create('now');
    $lot_finish_time = date_create($finish_time);
    $interval = date_diff($lot_finish_time, $current_time);
    return $interval->format('%a дн. %H:%I');
}

function validate_date($date, $key){ 
    if (!strtotime($date)) {
        return [$key => 'Введите дату'];
    }
    if (date_diff(date_create($date), date_create('now'))->days < 1) {
        return [$key => 'Введите завтрашнюю дату'];
    }

    return [];
}

function validate_number($number, $key){ 
    if (!is_numeric($number)) {
        return [$key => 'Введите число'];
    }
    if (intval($number) <= 0) {
        return [$key => 'Введите целое положительное число'];
    }

    return [];
}

function validate_category($id, $link){
    $id = intval($id);
    if (!$id) {
        return ['category' => 'Выберите категорию'];
    } else {
        $sql = "SELECT * FROM categories WHERE id=$id";
        $result = mysqli_query($link, $sql);
        if (!$result){
            // $error = mysqli_error($link);
            // show_error($error);
            return ['category' => 'Ошибка sql'];
        }
        if (!mysqli_num_rows($result)) {
            return ['category' => 'Выберите категорию'];
        }
    }
    return [];
}

function upload_img($name){
    if (is_uploaded_file($_FILES[$name]['tmp_name'])) {
		$tmp_name = $_FILES[$name]['tmp_name'];
		$path = $_FILES[$name]['name'];

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        
        if ($file_type !== "image/gif") {
            return ['error' => 'Загрузите картинку в формате GIF'];
		} 
        move_uploaded_file($tmp_name, 'uploads/' . $path);
        return ['path' => $path];
        
        // $filename = uniqid() . '.gif';
        // $gif['path'] = $filename;
        // move_uploaded_file($_FILES[$name]['tmp_name'], 'uploads/' . $filename);
		
	} else {
		return ['error' => 'Загрузите картинку в формате GIF'];
    }
}

function validate_img($name){
    if (is_uploaded_file($_FILES[$name]['tmp_name'])) {
		$tmp_name = $_FILES[$name]['tmp_name'];
		$path = $_FILES[$name]['name'];

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        
        if ($file_type !== "image/gif") {
			return ['file' => 'Загрузите картинку в формате GIF'];
        }
        
        return [];

	} else {
		return ['file' => 'Вы не загрузили файл'];
    }
}



/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}
?>