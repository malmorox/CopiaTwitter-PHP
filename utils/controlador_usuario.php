<?php

    require_once 'init.php';

    function obtenerInformacionDelUsuario($nombre) {
        global $db;

        $sql= "SELECT * FROM usuarios WHERE nombre = :nombre";
        $db->ejecuta($sql, $nombre);
        $usuario = $db->obtenDatos(BaseDatos::FETCH_FILA);

        return $usuario;
    }

    function obtenerNombreUsuarioPorId($id_usuario) {
        global $db;

        $db->ejecuta("SELECT nombre FROM usuarios WHERE id = :id", $id_usuario);
        $nombre_usuario = $db->obtenDatos(BaseDatos::FETCH_COLUMNA);

        return $nombre_usuario;
    }

    function editarInfoUsuario($nuevo_valor, $tipo_info, $id_usuario) {
        global $db;

        $sql = null;
        $resultado = false;

        switch ($tipo_info) {
            case MODIFICAR_TIPO_INFO_NOMBRE:
                $sql = "UPDATE usuarios SET nombre = :nuevo_nombre WHERE id = :id_usuario";
                $parametros = [$nuevo_valor];
                break;
            case MODIFICAR_TIPO_INFO_BIOGRAFIA:
                $sql = "UPDATE usuarios SET biografia = :nueva_biografia WHERE id = :id_usuario";
                $parametros = [$nuevo_valor];
                break;
            case MODIFICAR_TIPO_INFO_FOTOPERFIL:
                $ruta_imagen = guardarFotoDePerfil($nuevo_valor, $id_usuario);
                if ($ruta_imagen) {
                    $sql = "UPDATE usuarios SET foto_perfil = :ruta_imagen WHERE id = :id_usuario";
                    $parametros = [$ruta_imagen];
                } else {
                    return $resultado;
                }
                break;
            default:
                return $resultado;
        }

        if ($sql) {
            $parametros[] = $id_usuario;

            $db->ejecuta($sql, $parametros);
            $resultado = $db->getExecuted();
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