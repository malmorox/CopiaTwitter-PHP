<?php

    require_once 'conexion.php';

    function registrarUsuario($usuario, $contrasena, $email) {
        $db = conexion();
        $contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);
        $consulta = $db->prepare("INSERT INTO usuarios (usuario, contrasena, email) VALUES (:usuario, :contrasena, :email)");
        $consulta->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindParam(':contrasena', $contrasena_hasheada, PDO::PARAM_STR);
        $consulta->bindParam(':email', $email, PDO::PARAM_STR);
        $resultado = $consulta->execute();
        // Retorna true si hace el insert y false si no lo hace
        return $resultado;
    }

    function iniciarSesion($usuario, $contrasena) {
        $db = conexion();
        $consulta = $db->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return true;
        }

        return false;
    }

?>