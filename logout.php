<?php

    session_start();
    session_destroy();
    
    if (isset($_COOKIE['recuerdame'])) {
        $token = $_COOKIE['recuerdame'];
        marcarTokenConsumido($token);

        unset($_COOKIE['recuerdame']);
        setcookie('recuerdame', '', time() - 3600, '/');
    }

    header("location: login.php");
    exit;

?>