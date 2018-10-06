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