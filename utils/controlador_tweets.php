<?php

    require_once 'init.php';

    function publicarTweet($tweet, $id_usuario) {
        global $db;

        $sql = "INSERT INTO tweets (id_usuario, mensaje, fecha_hora) VALUES (:id_usuario, :mensaje, NOW())";
        $db->ejecuta($sql, [$id_usuario, $tweet]);
    }

    function mostrarTweets($id_usuario = null) {
        global $db;

        $consultaTweets = "
            SELECT 
                u.nombre AS nombre_usuario,
                u.foto_perfil AS foto_usuario,
                t.mensaje AS tweet,
                t.fecha_hora AS fecha_hora
            FROM tweets t 
            INNER JOIN usuarios u ON t.id_usuario = u.id";
            
        if ($id_usuario !== null) {
            $sql = $consultaTweets . " WHERE t.id_usuario = :id_usuario ORDER BY t.fecha_hora DESC";
            $parametros = [$id_usuario];
        } else {
            $sql = $consultaTweets . " ORDER BY t.fecha_hora DESC";
            $parametros = [];
        }

        $db->ejecuta($sql, $parametros);
        $tweets = $db->obtenDatos(BaseDatos::FETCH_TODOS);

        return $tweets;
    }

?>