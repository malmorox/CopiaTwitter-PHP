<?php

    require_once 'utils/correo.php';

    $error = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["enviar"])) {
        $correo_recuperacion = isset($_POST["correo_recuperacion"]) ? $_POST["correo_recuperacion"] : null;

        if (empty($correo_recuperacion)) {
            $error = true;
            $mensaje_error = "Por favor ingresa un correo electrónico";
        } elseif (!validarCorreo($correo_recuperacion)) {
            $error = true;
            $mensaje_error = "El correo electrónico ingresado no está asociado a ninguna cuenta";
        }

        if (!$error) {
            $correo_enviado = enviarCorreoRecuperacion($correo_recuperacion);
            if ($correo_enviado) {
                $mensaje_exito = "Se ha enviado exitosamente a tu email";
            }    
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Recuperar contraseña </title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2> Recupera tu contraseña </h2>
    <?php if (isset($correo_enviado) && $correo_enviado) : ?>
        <span class="exito"> <?= $mensaje_exito; ?> </span>
    <?php elseif (isset($correo_enviado)): ?> 
        <span class="error"> Ha habido un error al enviar el correo electrónico </span>
    <?php endif; ?>
    <form action="recuperar_contra.php" method="post">
        <label for="correo_recuperacion"> Correo electrónico asociado a la cuenta: </label> <br>
        <input type="email" name="correo_recuperacion"> <br>
        <?php if (isset($mensaje_error)) : ?>
            <span class="error"> <?= $mensaje_error; ?> </span>
        <?php endif; ?> <br>

        <input type="submit" name="enviar" value="ENVIAR CORREO">
    </form>
</body>
</html>