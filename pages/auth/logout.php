<?php

    require_once 'utils/init.php';

    session_destroy();
    
    if (isset($_COOKIE)) {
        $valor_cookie = $_COOKIE[NOMBRE_COOKIE_RECUERDAME];
        
        consumirTokenBD($valor_cookie);
        destruirCookie(NOMBRE_COOKIE_RECUERDAME);
    }

    header("location: login.php");
    exit;

?>