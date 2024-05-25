<?php

    require_once 'init.php';

    function generarToken() {
        return bin2hex(openssl_random_pseudo_bytes(NUMERO_CARACTERES_TOKEN_PREDETERMINADO));
    }

    function buscarIdUsuarioPorToken($token) {
        global $db;

        $sql = "SELECT id_usuario FROM tokens WHERE token = :token AND fecha_validez > NOW()";
        $db->ejecuta($sql, $token);
        $id_usuario = $db->obtenDatos(BaseDatos::FETCH_COLUMNA);
    
        if (empty($id_usuario)) {
            return null;
        }
    
        return $id_usuario;
    }

    function insertarTokenRecuperacionBD($token, $email) {
        global $db;
        
        $sqlIdUsuario = "SELECT id FROM usuarios WHERE email = :email";
        $db->ejecuta($sqlIdUsuario, $email);
        $id_usuario = $db->obtenDatos(BaseDatos::FETCH_COLUMNA);

        if (!$id_usuario) {
            return false;
        }

        $expiracion = date('Y-m-d H:i:s', (time() + TIEMPO_EXPIRACION_PREDETERMINADO));

        $sqlInsertarToken = "INSERT INTO tokens (token, id_usuario, fecha_validez) VALUES (:token, :id_usuario, :expiracion)";
        $db->ejecuta($sqlInsertarToken, [$token, $id_usuario, $expiracion]);

        return $db->getExecuted();
    }

    function insertarTokenRecuerdameBD($token, $id_usuario, $expiracion) {
        global $db;
        
        $sql = "INSERT INTO tokens (token, id_usuario, fecha_validez) VALUES (:token, :id_usuario, :expiracion)";
        $db->ejecuta($sql, [$token, $id_usuario, $expiracion]);
    }

    function validarTokenRecuperacion($token) {
        global $db;
        
        $sql = "SELECT * FROM tokens WHERE token = :token AND consumido = 0 AND fecha_validez > NOW()";
        $db->ejecuta($sql, $token);
        $token = $db->obtenDatos(BaseDatos::FETCH_FILA);

        // Si existe el token y no está consumido y no ha expirado retornará true, sino false
        return $token ? true : false;
    }

    function resetearContrasena($token, $nueva_contrasena) {
        global $db;
        
        $sqlIdUsuario = "SELECT id_usuario FROM tokens WHERE token = :token";
        $db->ejecuta($sqlIdUsuario, $token);
        $id_usuario = $db->obtenDatos(BaseDatos::FETCH_COLUMNA);

        if (!$id_usuario) {
            return false;
        }

        $nueva_contrasena_hasheada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        
        $sqlResetearContra = "UPDATE usuarios SET contrasena = :nueva_contrasena WHERE id = :id_usuario";
        $db->ejecuta($sqlResetearContra, [$nueva_contrasena_hasheada, $id_usuario]);

        return $db->getExecuted();
    }

    function consumirTokenBD($token) {
        global $db;

        $sql = "UPDATE tokens SET consumido = 1 WHERE token = :token";
        $db->ejecuta($sql, $token);
    }

?>