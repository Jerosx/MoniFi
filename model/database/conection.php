<?php

function create_conection() {
    /*Establece la conexión a la base de datos MySQL */

    $env = parse_ini_file('.env');

    $HOST = $env['DATABASE_HOST'];
    $USER = $env['DATABASE_USER'];
    $PASSWORD = $env['DATABASE_PASSWORD'];
    $NAME_DATABASE = $env['NAME_DATABASE'];

    $conection = new mysqli($HOST, $USER, $PASSWORD, $NAME_DATABASE);

    if ($conection->connect_error) {
        die("Conexión fallida: " . $conection->connect_error);
    }
    $conection->set_charset('utf8');

    return $conection;
}


?>