<?php

    require_once 'init.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    function validarCorreo($email) {
        global $db;

        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $db->ejecuta($sql, $email);
        $usuarioAsociado = $db->obtenDatos(BaseDatos::FETCH_FILA);
        
        // Si existe el usuario asociado con ese correo retorna true, sino false
        return $usuarioAsociado ? true : false;
    }

    function enviarCorreoRecuperacion($email) {
        $mail = new PHPMailer(true);

        try {
            $token = generarToken();

            if (insertarTokenRecuperacionBD($token, $email)) {
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host = 'smtp.educa.madrid.org';
                $mail->SMTPAuth = false;
                $mail->Username = 'malmoroxcabrera@educa.madrid.org';
                $mail->Password = 'Almoroxii1133';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->CharSet = 'UTF-8';
                $mail->setFrom('malmoroxcabrera@educa.madrid.org', 'Mini Twitter');
                $mail->addAddress($email, 'Marcos Almorox');
                $mail->Subject = 'Recuperación de contraseña';
                $mail->Body = "Haz click en el siguiente enlace para recuperar tu contraseña: http://localhost/twitter/resetear_contra_proceso.php?token=$token";

                $mail->send();
                return true;
            } else {
                return false;
            }
        } catch (Exception) {
            return false;
        }
    }

?>