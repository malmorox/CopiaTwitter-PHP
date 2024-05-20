<?php

    require_once 'init.php';

    function registrarUsuario($nombre, $contrasena, $email) {
        global $db;

        $contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, contrasena, email) VALUES (:nombre, :contrasena, :email)";
        $db->ejecuta($sql, [$nombre, $contrasena_hasheada, $email]);

        // Retorna true si hace el insert y false si no lo hace
        return $db->getExecuted();
    }

    function iniciarSesion($nombre, $contrasena) {
        global $db;

        $sql = "SELECT * FROM usuarios WHERE nombre = :nombre";
        $db->ejecuta($sql, $nombre);
        $usuario = $db->obtenDatos(BaseDatos::FETCH_FILA);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return true;
        }

        return false;
    }

?>