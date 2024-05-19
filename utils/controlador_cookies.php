<?php

    require_once 'controlador_tokens.php';
    require_once 'controlador_usuario.php';

    function verificarCookieRecuerdame() {
        if (!isset($_SESSION['usuario'])) {
            // Si no hay sesión iniciada verificamos la cookie 'recuerdame'
            if (isset($_COOKIE['recuerdame'])) {
                $valor_cookie = $_COOKIE['recuerdame'];
    
                $id_usuario = buscarIdUsuarioPorToken(substr($valor_cookie, 0, -1));
    
                if ($id_usuario !== null) {
                    $_SESSION['usuario'] = obtenerNombreUsuarioPorId($id_usuario);
                }
            }
        }
    }

    //verificarCookieRecuerdame();
    
?>