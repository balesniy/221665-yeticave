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
?>