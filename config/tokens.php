<?php

    require_once 'conexion.php';

    define("NUMERO_CARACTERES_TOKEN", 16);

    function insertarTokenRecuperacionBD($token, $email) {
        $db = conexion();
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

    function validarTokenReseteo($token) {
        $db = conexion();
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
        $db = conexion();
        $consultaIdUsuario = $db->prepare("SELECT id_usuario FROM tokens WHERE token = :token");
        $consultaIdUsuario->bindParam(':token', $token, PDO::PARAM_STR);
        $consultaIdUsuario->execute();
        $id_usuario = $consultaIdUsuario->fetchColumn();

        $nueva_contrasena_hasheada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $consultaResetear = $db->prepare("UPDATE usuarios SET contrasena = :nueva_contrasena WHERE id = :id_usuario");
        $consultaResetear->bindParam(':nueva_contrasena', $nueva_contrasena_hasheada, PDO::PARAM_STR);
        $consultaResetear->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $resultado = $consultaResetear->execute();

        return $resultado;
    }

    function eliminarTokenBD($token) {
        $db = conexion();
        $consulta = $db->prepare("DELETE FROM tokens WHERE token = :token");
        $consulta->bindParam(':token', $token, PDO::PARAM_STR);
        $consulta->execute();
    }

?>