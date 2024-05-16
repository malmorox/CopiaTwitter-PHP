<?php

    define ("DB_DATA", "mysql:host=localhost;dbname=practicando");
    define ("USERNAME", "malmorox");
    define ("PASSWORD" , "1234");
    
    function conexion() {
        try {
            $db = new PDO(DB_DATA, USERNAME, PASSWORD);
            return $db;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

?>