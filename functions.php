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
    $interval = date_diff($current_time, $lot_finish_time);
    return $interval->format('%a дн. %H:%I');
}

function validate_date($date, $key){ 
    if (!strtotime($date)) {
        return [$key => 'Введите дату'];
    }
    $diff = date_diff(date_create('now'), date_create($date));
    
    if ($diff->days < 1 || $diff->invert) {
        return [$key => 'Введите дату попозже'];
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
    $error = [];
    if (!$id) {
        $error['category'] = 'Выберите категорию';
    } else {
        $sql = "SELECT * FROM categories WHERE id=$id";
        $result = mysqli_query($link, $sql);
        if (!mysqli_num_rows($result)) {
            $error['category'] = 'Выберите категорию';
        }
    }
    return $error;
}

function validate_email($email, $link){
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $error = [];
    if (!$email) {
        $error['email'] = 'Введите корректный email';
    } else {
        $email = mysqli_real_escape_string($link, $email);
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($link, $sql);
        if (mysqli_num_rows($result)) {
            $error['email'] = 'email занят';
        }
    }
    return $error;
}

function validate_password($email, $password, $link){
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        return ['email' => 'Введите корректный email'];
    }
    $email = mysqli_real_escape_string($link, $email); 
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($link, $sql);
    if (!$result){
        return ['email' => 'Ошибка sql'];
    }
    if (!mysqli_num_rows($result)) {
        return ['email' => 'пользователь не найден'];
    }
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $auth = password_verify($password, $user['password']);
    if ($auth) {
        return ['user' => $user];
    }

    return ['password' => 'error'];
}

function validate_img($name, $required){
    $error = [];
    if (is_uploaded_file($_FILES[$name]['tmp_name'])) {
		$tmp_name = $_FILES[$name]['tmp_name'];
        $file_type = mime_content_type($tmp_name);
        
        if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
            $error['file'] = 'Загрузите картинку в формате PNG или JPG';
        }

	} else {
        if ($required){
            $error['file'] = 'Вы не загрузили файл';
        }
    }
	return $error;
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