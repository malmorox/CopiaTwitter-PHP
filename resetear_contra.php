<?php

    require_once 'config/tokens.php';

    $errores = [];

    if (!isset($_GET['token']) || !validarTokenReseteo($_GET['token'])) {
        header("Location: login.php");
        exit();
    } else {
        $token = htmlspecialchars($_GET['token']);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nueva_contrasena = isset($_POST['nueva_contrasena']) ? trim($_POST['nueva_contrasena']) : null;
        $confirmar_nueva_contrasena = isset($_POST['confirmar_nueva_contrasena']) ? trim($_POST['confirmar_nueva_contrasena']) : null;

        if (empty($nueva_contrasena)) {
            $errores['nueva_contrasena'] = "Debes introducir una nueva contraseña";
        } 

        if (empty($confirmar_nueva_contrasena)) {
            $errores['confirmar_nueva_contrasena'] = "Debes confirmar la contraseña";
        } elseif (!empty($nueva_contrasena) && $nueva_contrasena !== $confirmar_nueva_contrasena) {
            $errores['confirmar_nueva_contrasena'] = "Las contraseñas deben coincidir";
        }

        if (empty($errores)) {
            $reseteo_exitoso = resetearContrasena($token, $nueva_contrasena);
            if ($reseteo_exitoso) {
                eliminarTokenBD($token);
                $exito = "¡Tu contraseña ha sido modificada exitosamente!";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 2500);
                    </script>";
            } else {
                $errores['general'] = "Ha ocurrido un error al resetear la contraseña";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Resetear tu contraseña </title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2> Resetea tu contraseña </h2>
    <?php if (isset($exito)) : ?>
        <span class="exito"> <?= $exito ?> </span>
    <?php endif; ?>
    <?php if (isset($errores['general'])) : ?>
        <span class="error"> <?= $errores['general'] ?> </span>
    <?php endif; ?>
    <form action="<?= $_SERVER["REQUEST_URI"]; ?>" method="post">
        <label for="nueva_contrasena"> Nueva contraseña: </label> <br>
        <input type="password" name="nueva_contrasena"> <br>
        <?php if (isset($errores['nueva_contrasena'])): ?>
            <span class="error"> <?= $errores['nueva_contrasena']; ?> </span>
        <?php endif; ?> <br> 

        <label for="confirmar_nueva_contrasena"> Confirma la contraseña: </label> <br>
        <input type="password" name="confirmar_nueva_contrasena"> <br>
        <?php if (isset($errores['confirmar_nueva_contrasena'])): ?>
            <span class="error"> <?= $errores['confirmar_nueva_contrasena']; ?> </span>
        <?php endif; ?> <br> 

        <input type="submit" name="resetear" value="RESTABLECER">
    </form>
</body>
</html>