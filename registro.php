<?php

    require_once 'utils/init.php';

    $errores = [];

    verificarCookieRecuerdame();
    if (isset($_SESSION['usuario'])) {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : null;
        $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : null;
        $confirmar_contrasena = isset($_POST['confirmar_contrasena']) ? trim($_POST['confirmar_contrasena']) : null;
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    
        if (empty($usuario)) {
            $errores['usuario'] = "Debes introducir un nombre de usuario";
        }
    
        if (empty($contrasena)) {
            $errores['contrasena'] = "Debes introducir una contraseña";
        } 

        if (empty($confirmar_contrasena)) {
            $errores['confirmar_contrasena'] = "Debes confirmar la contraseña";
        } elseif (!empty($contrasena) && $contrasena !== $confirmar_contrasena) {
            $errores['confirmar_contrasena'] = "Las contraseñas no coinciden";
        }
    
        if (empty($email)) {
            $errores['email'] = "Debes introducir un correo electronico";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = "El correo electrónico no es valido";
        }
    
        if (empty($errores)) {
            $registro_exitoso = registrarUsuario($nombre, $contrasena, $email);
    
            if ($registro_exitoso) {
                $info_usuario = obtenerInformacionDelUsuario($nombre);
                
                $_SESSION['usuario'] = $info_usuario;
                
                header("Location: index.php");
                exit();
            } else {
                $errores['registro'] = "Hubo un error al registrar el usuario. Por favor, inténtalo de nuevo.";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Registro </title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h1> Regístrate </h1>
    <?php if (isset($errores['registro'])): ?>
        <p class="error"><?= $errores['registro']; ?></p>
    <?php endif; ?>
    <form action="registro.php" method="post">
        <label for="nombre"> Nombre de usuario: </label> <br>
        <input type="text" name="nombre" value="<?= isset($nombre) ? htmlspecialchars($nombre) : ''; ?>"> <br>
        <?php if (isset($errores['nombre'])): ?>
            <span class="error"><?= $errores['nombre']; ?> </span>
        <?php endif; ?> <br>

        <label for="contrasena"> Contraseña: </label> <br>
        <input type="password" name="contrasena" value="<?= isset($contrasena) ? htmlspecialchars($contrasena) : ''; ?>"> <br>
        <?php if (isset($errores['contrasena'])): ?>
            <span class="error"><?= $errores['contrasena']; ?> </span>
        <?php endif; ?> <br>

        <label for="confirmar_contrasena"> Confirmar contraseña: </label> <br>
        <input type="password" name="confirmar_contrasena" value="<?= isset($confirmar_contrasena) ? htmlspecialchars($confirmar_contrasena) : ''; ?>"> <br>
        <?php if (isset($errores['confirmar_contrasena'])): ?>
            <span class="error"><?= $errores['confirmar_contrasena']; ?> </span>
        <?php endif; ?> <br>

        <label for="email"> Correo electrónico: </label> <br>
        <input type="text" name="email" value="<?= isset($email) ? htmlspecialchars($email) : ''; ?>"> <br>
        <?php if (isset($errores['email'])): ?>
            <span class="error"><?= $errores['email']; ?> </span>
        <?php endif; ?> <br>

        <input type="submit" value="Registrar"> <br> <br>
    </form>
    <a href="login.php"> Ya tengo cuenta </a>
</body>
</html>