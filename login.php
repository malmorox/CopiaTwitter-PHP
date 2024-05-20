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
        $recordar = isset($_POST['recuerdame']) ? true : false;

        if (!empty($nombre) && !empty($contrasena)) {
            $login_exitoso = iniciarSesion($nombre, $contrasena); 
        
            if (!$login_exitoso) {
                $errores['credenciales'] = "Credenciales incorrectas.";
            }
        
            if (empty($errores)) {
                $info_usuario = obtenerInformacionDelUsuario($nombre);
                
                $_SESSION['usuario'] = $info_usuario;

                if ($recordar) {
                    $token = generarToken();
                    $expiracion = time() + TIEMPO_EXPIRACION_PREDETERMINADO;

                    insertarTokenRecuerdameBD($token, $info_usuario['id'], date('Y-m-d H:i:s', $expiracion), VALOR_TOKEN_CONSUMIDO_PREDETERMINADO);
        
                    setcookie('recuerdame', $token, $expiracion, '/');
                }
            
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
        <label for="nombre"> Nombre de usuario: </label> <br>
        <input type="text" name="nombre" value="<?= isset($nombre) ? $nombre : ''; ?>"> <br>
        <?php if (isset($errores['nombre'])): ?>
            <span class="error"> <?= $errores['nombre']; ?> </span>
        <?php endif; ?> <br> 

        <label for="contrasena"> Contraseña: </label> <br>
        <input type="password" name="contrasena" value="<?= isset($contrasena) ? $contrasena : ''; ?>"> <br>
        <?php if (isset($errores['contrasena'])): ?>
            <span class="error"> <?= $errores['contrasena']; ?> </span>
        <?php endif; ?> <br> 
        

        <input type="checkbox" name="recuerdame" <?= isset($recordar) && $recordar ? 'checked' : ''; ?>>
        <label for="recuerdame"> Recuérdame </label> <br> <br>

        <input type="submit" name="login" value="ENTRAR"> <br> <br>
    </form>
    <a href="registro.php"> Registrarse </a> <br>
    <a href="recuperar_contra.php"> ¿Olvidaste tu contraseña? </a>
</body>
</html>