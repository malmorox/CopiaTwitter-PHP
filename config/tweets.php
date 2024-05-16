<?php

    require_once 'conexion.php';

    function publicarTweet($tweet, $id_usuario) {
        $db = conexion();
        $consulta = $db->prepare("INSERT INTO tweets (id_usuario, mensaje, fecha_hora) VALUES (:id_usuario, :mensaje, NOW())");
        $consulta->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $consulta->bindParam(':mensaje', $tweet, PDO::PARAM_STR);
        $resultado = $consulta->execute();

        return $resultado;
    }

    function mostrarTweets($id_usuario = null) {
        $db = conexion();
        $consultaTweets = "
            SELECT 
                u.usuario AS nombre_usuario,
                u.foto_perfil AS foto_usuario,
                t.mensaje AS tweet,
                t.fecha_hora AS fecha_hora
            FROM tweets t 
            INNER JOIN usuarios u ON t.id_usuario = u.id";
        if ($id_usuario !== null) {
            $consulta = $db->prepare($consultaTweets . " WHERE t.id_usuario = :id_usuario ORDER BY t.fecha_hora DESC");
            $consulta->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        } else {
            $consulta = $db->prepare($consultaTweets . " ORDER BY t.fecha_hora DESC");
        }
        $consulta->execute();
        $tweets = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $tweets;
    }

?>