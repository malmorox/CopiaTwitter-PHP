<?php

    require_once 'controlador_tokens.php';
    require_once 'controlador_usuario.php';

    // Función para destruir una cookie
    function destruirCookie($nombre_cookie) {
        unset($_COOKIE[$nombre_cookie]);
        setcookie($nombre_cookie, '', time() - 3600, '/');
    }

    // Función para verificar si hay una sesión iniciada y si no, verificar la cookie 'recuerdame' para setear la sesión
    function verificarCookieRecuerdame() {
        if (!isset($_SESSION['usuario'])) {
            // Si no hay sesión iniciada verificamos la cookie 'recuerdame'
            if (isset($_COOKIE['recuerdame'])) {
                $valor_cookie = $_COOKIE['recuerdame'];
                // El valor de la cookie 'recuerdame' es el token
                $id_usuario = buscarIdUsuarioPorToken($valor_cookie);
                
                if ($id_usuario !== null) {
                    $_SESSION['usuario'] = obtenerInformacionDelUsuario(obtenerNombreUsuarioPorId($id_usuario));
                }
            }
        }
    }

    //verificarCookieRecuerdame();
    
?>