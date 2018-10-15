<?php

require_once 'init.php';

$errors = [];
$lot = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $sql = "INSERT INTO bets (amount, user_id, lot_id) VALUES(?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [
            $_POST['amount'], $user['id'], $_POST['id']
        ]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {

            header('Location: lot.php?id='.isset($_POST['id']) ? intval($_POST['id']) : 0);
        }
} else {
    header('Location: index.php');
}


?>