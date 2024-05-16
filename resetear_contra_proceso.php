<?php

    require_once 'config/tokens.php';

    if (!isset($_GET['token'])) {
        header("Location: login.php");
        exit();
    } else {
        $token = htmlspecialchars($_GET['token']);
        if (!validarTokenReseteo($token)) {
            $error = "Este enlace de restablecimiento de contraseña no es válido o ha caducado";
        } else {
            header("Location: resetear_contra.php?token=$token");
            exit();
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Error al restablecer tu contraseña </title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2> Error al intentar restablecer tu contraseña </h2>
    <?php if (isset($error)) : ?>
        <p> <?= $error; ?> </p>
    <?php endif; ?>
    <a href="login.php"> Volver al inicio de sesión </a>
    </div>
</body>
</html>