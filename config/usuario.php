<?php

    require_once 'conexion.php';

    function obtenerInformacionDelUsuario($usuario) {
        $db = conexion();
        $consulta = $db->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        return $usuario;
    }

    function editarInfoUsuario($nuevo_valor, $tipo_info, $id_usuario) {
        $db = conexion();
        $consulta = null;
        $resultado = false;

        switch ($tipo_info) {
            case 'nombre':
                $consulta = $db->prepare("UPDATE usuarios SET usuario = :nuevo_usuario WHERE id = :id_usuario");
                $consulta->bindParam(':nuevo_usuario', $nuevo_valor, PDO::PARAM_STR);
                break;
            case 'biografia':
                $consulta = $db->prepare("UPDATE usuarios SET biografia = :nueva_biografia WHERE id = :id_usuario");
                $consulta->bindParam(':nueva_biografia', $nuevo_valor, PDO::PARAM_STR);
                break;
            case 'foto_perfil':
                $ruta_imagen = guardarFotoDePerfil($nuevo_valor, $id_usuario);
                if ($ruta_imagen) {
                    $consulta = $db->prepare("UPDATE usuarios SET foto_perfil = :ruta_imagen WHERE id = :id_usuario");
                    $consulta->bindParam(':ruta_imagen', $ruta_imagen, PDO::PARAM_STR);
                } 
                break;
            default:
                return $resultado;
        }

        if ($consulta) {
            $consulta->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $resultado = $consulta->execute();
        }

        return $resultado;
    }
    
    function guardarFotoDePerfil($foto_perfil, $id_usuario) {
        $directorio = "media/fotos_perfil/";

        $nombre_archivo = basename($foto_perfil["name"]);
        $archivo = $directorio . $nombre_archivo;
        $formato_imagen = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

        if ($formato_imagen != "jpg" && $formato_imagen != "png" && $formato_imagen != "jpeg" && $formato_imagen != "gif") {
            return false;
        }

        $contador = 1;
        while (file_exists($archivo)) {
            $nombre_sin_extension = pathinfo($nombre_archivo, PATHINFO_FILENAME);
            $archivo = $directorio . $nombre_sin_extension . '(' . $contador . ').' . $formato_imagen;
            $contador++;
        }

        $foto_real = getimagesize($foto_perfil["tmp_name"]);
        if($foto_real !== false) {
            $subir = 1;
        } else {
            $subir = 0;
        }

        if ($subir == 1) {
            if (move_uploaded_file($foto_perfil["tmp_name"], $archivo)) {
                return $archivo;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

?>