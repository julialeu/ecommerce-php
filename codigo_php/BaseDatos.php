<?php

function findUser($username, $email) {
    // Nos conectamos a la base de datos world
    $DB = createConnectionDataBase("pac3_daw");

    // Definimos la consulta para obtener todos los datos de la tabla user.
    $sql = "SELECT * FROM user WHERE Email = '$email' AND FullName = '$username'";

    // Hacemos la consulta y guardamos el resultado en $result
    $result = mysqli_query($DB, $sql);

    if (mysqli_num_rows($result) > 0) {
        return $result;
    }
}

function createConnectionDataBase($database) {

    // Datos de conexión
    $host = "localhost";
    $user = "root";
    $password = "";

    // Establecemos la conexión con la base de datos
    $conexion = mysqli_connect($host, $user, $password, $database);

    // Si hay un error en la conexión, lo mostramos y detenemos.
    if (!$conexion) {
        die("<br>Error de conexión con la base de datos: " . mysqli_connect_error());
    }

    return $conexion;
}

//function login($username, $email)
//{
//    $user = findUser($username, $email);
//
//    return $user !== null;
//}

