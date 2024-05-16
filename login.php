<?php

    require_once 'config/auth.php';

    $errores = [];

    session_start();

    if (isset($_SESSION['usuario'])) {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : null;
        $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : null;
    
        if (!empty($usuario) && !empty($contrasena)) {
            $login_exitoso = iniciarSesion($usuario, $contrasena); 
        
            if (!$login_exitoso) {
                $errores['credenciales'] = "Credenciales incorrectas.";
            }
        
            if (empty($errores)) {
                session_start();
                $_SESSION['usuario'] = $usuario;
            
                header("Location: index.php");
                exit();
            }
        } else {
            $errores['credenciales'] = "Debes completar ambos campos.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Iniciar sesión </title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h1> Iniciar sesión </h1>
    <?php if (isset($errores['credenciales'])): ?>
            <span class="error"> <?= $errores['credenciales']; ?> </span> <br>
        <?php endif; ?>
    <form action="" method="post">
        <label for="usuario"> Nombre de usuario: </label> <br>
        <input type="text" name="usuario" value="<?= isset($usuario) ? $usuario : ''; ?>"> <br>
        <?php if (isset($errores['usuario'])): ?>
            <span class="error"> <?= $errores['usuario']; ?> </span>
        <?php endif; ?> <br> 

        <label for="contrasena"> Contraseña: </label> <br>
        <input type="password" name="contrasena" value="<?= isset($contrasena) ? $contrasena : ''; ?>"> <br>
        <?php if (isset($errores['contrasena'])): ?>
            <span class="error"> <?= $errores['contrasena']; ?> </span>
        <?php endif; ?> <br> 
        

        <input type="checkbox" name="recuerdame">
        <label for="recuerdame" <?= isset($_POST['recuerdame']) ? 'checked' : ''; ?>> Recuérdame </label> <br> <br>

        <input type="submit" name="login" value="ENTRAR"> <br> <br>
    </form>
    <a href="registro.php"> Registrarse </a> <br>
    <a href="recuperar_contra.php"> ¿Olvidaste tu contraseña? </a>
</body>
</html>