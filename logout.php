<?php
    session_start();
    $_SESSION['user'] = [];
    header('Location: index.php');
    ?>