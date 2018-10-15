<?php

require_once 'init.php';

$errors = [];
$required = ['cost', 'id'];
$numbers = ['cost'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


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


    $sql = "INSERT INTO bets (amount, user_id, lot_id) VALUES(?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [
            $_POST['cost'], $user['id'], $_POST['id']
        ]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {

            header('Location: lot.php?id='.isset($_POST['id']) ? intval($_POST['id']) : 0);
        }
} else {
    header('Location: index.php');
}


?>