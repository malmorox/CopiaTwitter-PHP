<?php

    require_once 'utils/init.php';

    session_destroy();
    
    if (isset($_COOKIE['recuerdame'])) {
        $valor_cookie = $_COOKIE['recuerdame'];
        //marcarTokenConsumido($valor_cookie);

        unset($valor_cookie);
        setcookie('recuerdame', '', time() - 3600, '/');
    }

    header("location: login.php");
    exit;

?>