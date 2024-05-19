<?php

    require_once 'init.php';

    function generarToken() {
        return bin2hex(openssl_random_pseudo_bytes(NUMERO_CARACTERES_TOKEN_PREDETERMINADO));
    }

    function insertarTokenRecuperacionBD($token, $email) {
        global $db;
        
        $sqlIdUsuario = "SELECT id FROM usuarios WHERE email = :email";
        $db->ejecuta($sqlIdUsuario, $email);
        $id_usuario = $db->obtenDatos(BaseDatos::FETCH_COLUMNA);

        if (!$id_usuario) {
            return false;
        }

        $sqlInsertarToken = "INSERT INTO tokens (token, id_usuario) VALUES (:token, :id_usuario)";
        $db->ejecuta($sqlInsertToken, [$token, $id_usuario]);

        return $db->getExecuted();
    }

    function insertarTokenRecuerdameBD($token, $id_usuario, $expiracion, $consumido) {
        global $db;
        $consultaIdUsuario = $db->prepare("SELECT id FROM usuarios WHERE email = :email");
        $consultaIdUsuario->bindParam(':email', $email, PDO::PARAM_STR);
        $consultaIdUsuario->execute();
        $id_usuario = $consultaIdUsuario->fetchColumn();

        $consultaToken = $db->prepare("INSERT INTO tokens (token, id_usuario) VALUES (:token, :id_usuario)");
        $consultaToken->bindParam(':token', $token, PDO::PARAM_STR);
        $consultaToken->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $resultado = $consultaToken->execute();

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    function validarTokenRecuperacion($token) {
        global $db;
        $consulta = $db->prepare("SELECT * FROM tokens WHERE token = :token");
        $consulta->bindParam(':token', $token, PDO::PARAM_STR);
        $consulta->execute();
        $token = $consulta->fetch(PDO::FETCH_ASSOC);
        // Si existe token retornará true, sino false
        if ($token) {
            return true;
        }

        return false;
    }

    function resetearContrasena($token, $nueva_contrasena) {
        global $db;
        
        $sqlIdUsuario = "SELECT id_usuario FROM tokens WHERE token = :token";
        $db->ejecuta($sqlIdUsuario, $token);
        $id_usuario = $db->obtenDatos(BaseDatos::FETCH_COLUMNA);

        $nueva_contrasena_hasheada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        
        $consultaResetear = $db->prepare("UPDATE usuarios SET contrasena = :nueva_contrasena WHERE id = :id_usuario");
        $consultaResetear->bindParam(':nueva_contrasena', $nueva_contrasena_hasheada, PDO::PARAM_STR);
        $consultaResetear->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $resultado = $consultaResetear->execute();

        return $resultado;
    }

    function eliminarTokenBD($token) {
        global $db;
        $consulta = $db->prepare("DELETE FROM tokens WHERE token = :token");
        $consulta->bindParam(':token', $token, PDO::PARAM_STR);
        $consulta->execute();
    }

?>