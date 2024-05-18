<?php

    require_once 'init.php';

    function registrarUsuario($usuario, $contrasena, $email) {
        global $db;

        $contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (usuario, contrasena, email) VALUES (:usuario, :contrasena, :email)";
        $db->ejecuta($sql, [$usuario, $contrasena_hasheada, $email]);

        // Retorna true si hace el insert y false si no lo hace
        return $db->getExecuted();
    }

    function iniciarSesion($usuario, $contrasena) {
        global $db;

        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
        $db->ejecuta($sql, $usuario);
        $usuario = $db->obtenDatos(BaseDatos::FETCH_FILA);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return true;
        }

        return false;
    }

?>