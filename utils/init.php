<?php

    define('PATH_FUNCIONES', dirname(__FILE__) . '/');
    define('PATH_BASEDATOS', 'db/');
    define('TIEMPO_EXPIRACION_PREDETERMINADO', 7 * 24 * 60 * 60);

    spl_autoload_register(function ($clase) {
        require PATH_BASEDATOS . $clase . '.php';
    });

    foreach (glob(PATH_FUNCIONES . '*.php') as $fichero) {
        require_once $fichero;
    }

    $db = DWESBaseDatos::obtenerInstancia();
    $db->inicializa(
        'practicando',
        'malmorox',
        '1234');

    session_start();

?>