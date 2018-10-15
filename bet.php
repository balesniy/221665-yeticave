<?php

require_once 'init.php';

$errors = [];
$required = ['cost', 'id'];
$numbers = ['cost'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !(empty($user))) {

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        $sql = "SELECT *
        FROM lots
        WHERE lots.id=$id";

        $result = mysqli_query($link, $sql);
        if (!$result){
            $error = mysqli_error($link);
            show_error($error);
        }
        if (!mysqli_num_rows($result)) {
            http_response_code(404);
            show_error('Лот с этим идентификатором не найден');
        }
        $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $user_id = $user['id'];

        if($lot['user_id'] == $user_id){
            http_response_code(400);
            show_error('это ваш лот');
        }

        $sql = "SELECT *
        FROM bets
        WHERE lots_id=$id AND user_id=$user_id";

        $result = mysqli_query($link, $sql);
        if (!$result){
            $error = mysqli_error($link);
            show_error($error);
        }
        if (mysqli_num_rows($result)) {
            $errors['user'] = 'ставка уже сделана';
        }


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

    if ($lot['price']+$lot['amount_step']>intval($_POST['cost'])){
        $errors['cost'] = 'слишком мало';
    }

    if (!count($errors)) {
        $sql = "INSERT INTO bets (amount, user_id, lot_id) VALUES(?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [
            $_POST['cost'], $user['id'], $_POST['id']
        ]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {

            header('Location: lot.php?id='.isset($_POST['id']) ? intval($_POST['id']) : 0);
        }
    }
    
} else {
    header('Location: index.php');
}


?>