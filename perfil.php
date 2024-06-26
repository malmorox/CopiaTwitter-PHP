<?php

    require_once 'utils/init.php';
    require_once 'Tweet.php';

    $errores = [];

    verificarCookieRecuerdame();
    // Si no hay una sesión iniciada en este punto redirigimos a la página de inicio de sesión
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit();
    }

    $usuario = $_SESSION['usuario'];
    $tweets_usuario = mostrarTweets($usuario['id']);
    $cambio_exitoso = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar-cambios'])) {
        $nuevo_nombre_usuario = isset($_POST['nuevo_nombre_usuario']) ? trim($_POST['nuevo_nombre_usuario']) : null;
        $nueva_biografia_usuario = isset($_POST['nueva_biografia_usuario']) ? trim($_POST['nueva_biografia_usuario']) : null;

        $cambio_nombre_exitoso = false;
        $cambio_biografia_exitoso = false;
        $cambio_foto_exitoso = false;
        
        if (!empty($nuevo_nombre_usuario) && $nuevo_nombre_usuario !== $usuario['nombre']) {
            $cambio_nombre_exitoso = editarInfoUsuario($nuevo_nombre_usuario, MODIFICAR_TIPO_INFO_NOMBRE, $usuario['id']);
        }
    
        if (!empty($nueva_biografia_usuario) && $nueva_biografia_usuario !== $usuario['biografia']) {
            $cambio_biografia_exitoso = editarInfoUsuario($nueva_biografia_usuario, MODIFICAR_TIPO_INFO_BIOGRAFIA, $usuario['id']);
        }

        if (isset($_FILES['nueva_foto_perfil']) && $_FILES['nueva_foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $cambio_foto_exitoso = editarInfoUsuario($_FILES['nueva_foto_perfil'], MODIFICAR_TIPO_INFO_FOTOPERFIL, $usuario['id']);
        }

        if ($cambio_nombre_exitoso || $cambio_biografia_exitoso || $cambio_foto_exitoso) {
            $cambio_exitoso = true;
        } else if ($nuevo_nombre_usuario === $usuario['nombre'] && 
                    $nueva_biografia_usuario === $usuario['biografia'] &&
                    (!isset($_FILES['nueva_foto_perfil']) || $_FILES['nueva_foto_perfil']['error'] === UPLOAD_ERR_NO_FILE)) {
            echo "<script>cerrarPopup();</script>";
        } else {
            $mensaje_error = "Ha habido un problema al modificar el perfil";
        }

        if ($cambio_exitoso) {
            if ($cambio_nombre_exitoso) {
                $usuario['nombre'] = $nuevo_nombre_usuario;
            }
            $usuario = obtenerInformacionDelUsuario($usuario['nombre']);
            $_SESSION['usuario'] = $usuario;

            header("refresh:0.5");
            die();
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
            <span class="perfil-nombre-usuario"> @<?= $usuario['nombre']; ?> </span> <br>
            <span class="perfil-biografia-usuario"> <?= $usuario['biografia']; ?> </span> <br>
        </section>
        <section class="perfil-editar-usuario">
            <button id="boton-editar-perfil"> Editar perfil </button>
        </section>
    </div>
    
    <div class="popup-fondo">
        <div class="popup-editar-perfil">
            <?php if (isset($mensaje_error)): ?>
                <span class="error"> <?= $mensaje_error ?> </span> <br>
            <?php endif; ?>
            <form action="perfil.php" method="post" enctype="multipart/form-data">
                <label for="nueva_foto_perfil"> Foto de perfil: </label> <br>
                <input type="file" name="nueva_foto_perfil"> <br> <br>

                <label for="nuevo_nombre_usuario"> Nombre de usuario: </label> <br>
                <input type="text" name="nuevo_nombre_usuario" value="<?= $usuario['nombre'] ?>"> <br> <br>

                <label for="nueva_biografia_usuario"> Biografía: </label> <br>
                <textarea name="nueva_biografia_usuario"><?= $usuario['biografia'] ?></textarea> <br> <br>
                
                <button id="boton-cancelar-cambios"> Cancelar </button>
                <input type="submit" name="guardar-cambios" value="GUARDAR CAMBIOS">
            </form>
        </div>
    </div>
    <hr>
    <h2> Tus tweets: </h2>
    <?php if (!empty($tweets_usuario)): ?>
        <div class="todos-tweets">
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