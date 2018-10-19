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

function get_time($finish_time, $invert = false){
    $current_time = date_create('now');
    $lot_finish_time = date_create($finish_time);
    $interval = date_diff($current_time, $lot_finish_time);
    if($interval->invert){
        if($interval->days < 1 && $interval->h>=1){
            $result = $interval->format('%h ч. назад');
        }

        if($interval->days < 1 && $interval->h<1){
            $result = $interval->i? $interval->format('%i мин. назад') : 'только что';
        }

        if($interval->days < 3 && $interval->days >= 1){
            $result = $interval->format('%a дн. назад');
        }

        if($interval->days >=3){
            $result = $lot_finish_time->format('d-m-Y');
        }
        
    } elseif ($invert){
        $result = 'только что';
    }
    
    else {
        $result = $interval->format('%a дн. %h:%i');
    }
    return $result;
}

function validate_date($date, $key){ 
    $result = [];
    if (!strtotime($date)) {
        $result = [$key => 'Введите дату'];
    } else {
        $diff = date_diff(date_create('now'), date_create($date));
    
        if ($diff->invert || $diff->days < 1) {
            $result = [$key => 'выберите дату больше текущей хотябы на один день'];
        }
    }
    return $result;
}

function validate_number($number, $key){ 
    $result = [];
    
    if (!is_numeric($number)) {
        $result = [$key => 'Введите число'];
    }
    elseif(intval($number) <= 0) {
        $result =  [$key => 'Введите целое положительное число'];
    }
    return $result;
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

function get_by_email($email, $link, $exist) {
    $error = [];
    $user = null;
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $error['email'] = 'Введите корректный email';
    } else {
        $email = mysqli_real_escape_string($link, $email);
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($link, $sql);
        if (mysqli_num_rows($result) && $exist) {
            $error['email'] = 'email занят';
        }
        if (mysqli_num_rows($result) && !$exist) {
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        if (!mysqli_num_rows($result) && !$exist) {
            $error['email'] = 'пользователь не найден';
        }
    }
   
    return $exist ? $error : ['error' => $error, 'user' => $user];
}

function validate_email($email, $link){
    return get_by_email($email, $link, true);
}

function validate_password($email, $password, $link){
    $result = get_by_email($email, $link, false);
    $user = $result['user'];
    $error = $result['error'];

    if ($user) {
        $auth = password_verify($password, $user['password']);
        $error = $auth ? ['user' => $user] : ['password' => 'error'];
    }

    return $error;
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