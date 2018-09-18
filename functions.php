<?php
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require_once $name;

    $result = ob_get_clean();

return $result;
};
function price_format($price){
    $price = ceil($price);
    $rub_format = $price>999 ? number_format($price, 0, ".", " ") : $price;
    return "$rub_format ₽";
};
?>