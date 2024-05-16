<?php

    require_once 'config/usuario.php';
    require_once 'config/tweets.php';
    require_once 'Tweet.php';

    $errores = [];

    session_start();

    if (isset($_COOKIE['recuerdame'])) {
        $token = $_COOKIE['recuerdame'];

        $id_usuario = buscarIdUsuarioPorToken(substr($token, 0, -1));

        if ($id_usuario !== null) {
            $_SESSION['usuario'] = obtenerNombreUsuario($id_usuario);
        }
    }

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
        exit();
    }

    $usuario = obtenerInformacionDelUsuario($_SESSION['usuario']);
    $tweets_usuario = mostrarTweets($usuario['id']);
    $cambio_exitoso = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nuevo_nombre_usuario = isset($_POST['nuevo_nombre_usuario']) ? trim($_POST['nuevo_nombre_usuario']) : null;
        $nueva_biografia_usuario = isset($_POST['nueva_biografia_usuario']) ? trim($_POST['nueva_biografia_usuario']) : null;
        
        if (!empty($nuevo_nombre_usuario) && $nuevo_nombre_usuario !== $usuario['usuario']) {
            $cambio_nombre_exitoso = editarInfoUsuario($nuevo_nombre_usuario, 'nombre', $usuario['id']);
            if ($cambio_nombre_exitoso) {
                $_SESSION['usuario'] = $nuevo_nombre_usuario;
                $cambio_exitoso = true;
            } else {
                $mensaje_error = "Error al modificar el nombre de usuario";
            }
        }
    
        if (!empty($nueva_biografia_usuario) && $nueva_biografia_usuario !== $usuario['biografia']) {
            $cambio_biografia_exitoso = editarInfoUsuario($nueva_biografia_usuario, 'biografia', $usuario['id']);
            if ($cambio_biografia_exitoso) {
                $cambio_exitoso = true;
            } else {
                $mensaje_error = "Error al modificar la biografia";
            }
        }

        if (isset($_FILES['nueva_foto_perfil']) && $_FILES['nueva_foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $cambio_foto_exitoso = editarInfoUsuario($_FILES['nueva_foto_perfil'], 'foto_perfil', $usuario['id']);
            if ($cambio_foto_exitoso) {
                $cambio_exitoso = true;
            } else {
                $mensaje_error = "Error al modificar la foto de perfil";
            }
        }

        if ($cambio_exitoso) {
            header("Location: perfil.php");
        } elseif (!empty($error_mensaje)) {
            $error_mensaje = "Ha habido un problema al modificar el perfil.";
        }
    }

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Perfil de usuario </title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="perfil-configuracion-usuario">
        <section class="perfil-info-usuario">
            <div class="perfil-foto-usuario">
                <?php if (!empty($usuario['foto_perfil'])): ?>
                    <img src="<?= $usuario['foto_perfil']; ?>" alt="">
                <?php endif; ?>
            </div>
            <span class="perfil-nombre-usuario"> @<?= $usuario['usuario']; ?> </span> <br>
            <span class="perfil-biografia-usuario"> <?= $usuario['biografia']; ?> </span> <br>
        </section>
        <section class="perfil-editar-usuario">
            <button id="boton-editar-perfil"> Editar perfil </button>
        </section>
    </div>
    <?php if (isset($error_mensaje)): ?>
        <span class="error"> <?= $error_mensaje ?> </span>
    <?php endif; ?>
    <div class="popup-fondo">
        <div class="popup-editar-perfil">
            <form action="perfil.php" method="post" enctype="multipart/form-data">
                <label for="nueva_foto_perfil"> Foto de perfil: </label> <br>
                <input type="file" name="nueva_foto_perfil"> <br> <br>

                <label for="nuevo_nombre_usuario"> Nombre de usuario: </label> <br>
                <input type="text" name="nuevo_nombre_usuario" value="<?= $usuario['usuario'] ?>"> <br> <br>

                <label for="nueva_biografia_usuario"> Nueva biografía: </label> <br>
                <textarea name="nueva_biografia_usuario"><?= $usuario['biografia'] ?></textarea> <br> <br>

                <input type="submit" name="guardar" value="GUARDAR CAMBIOS">
            </form>
        </div>
    </div>
    <hr>
    <h2> Tus tweets: </h2>
    <?php if (!empty($tweets_usuario)): ?>
        <div>
            <?php foreach ($tweets_usuario as $info_tweet): ?>
                <?php $tweet = new Tweet($info_tweet['nombre_usuario'], $info_tweet['foto_usuario'], $info_tweet['tweet'], $info_tweet['fecha_hora']); ?>
                <?= $tweet; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p> No has publicado ningún tweet todavía. </p>
    <?php endif; ?>

    <a href="logout.php"> Cerrar sesión </a>
    <script src="js/script.js"></script>
</body>
</html>