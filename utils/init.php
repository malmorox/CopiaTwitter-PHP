<?php

    // Rutas base para las funciones y la base de datos
    define('PATH_FUNCIONES', dirname(__FILE__) . '/');
    define('PATH_BASEDATOS', 'db/');
    // Define el tiempo de expiración predeterminado para tokens/cookies/etc
    define('TIEMPO_EXPIRACION_PREDETERMINADO', 7 * 24 * 60 * 60);
    // Define el número de caracteres que tendrá el token
    define("NUMERO_CARACTERES_TOKEN_PREDETERMINADO", 128);

    // Autoload para cargar automáticamente las clases desde el directorio de base de datos
    spl_autoload_register(function ($clase) {
        require PATH_BASEDATOS . $clase . '.php';
    });

    // Cargar todas las funciones desde el directorio en el que nos encontramos
    foreach (glob(PATH_FUNCIONES . '*.php') as $fichero) {
        require_once $fichero;
    }

    // Obtener una instancia única de la base de datos e inicializar la conexión
    $db = BaseDatos::obtenerInstancia();
    $db->inicializa(
        'practicando',
        'malmorox',
        '1234');

    session_start();

?>