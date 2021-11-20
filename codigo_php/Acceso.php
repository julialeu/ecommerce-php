<?php

session_start();


if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}

include 'BaseDatos.php';

$user = getUserFromSession();

$role = getRol($user);

if($role === User::ROLE_SUPERADMIN || $role === User::ROLE_AUTHORIZED || user::ROLE_REGISTERED ) {

    echo "<a href=\"Articulos.php\">Artículos</a>"."<br>";

}

if($role === User::ROLE_SUPERADMIN){

    echo "<a href=\"Usuarios.php\">Usuarios</a>"."<br>";
}

    echo "<a href=\"Index.php\">Volver</a>"."<br>";

var_dump($_SESSION['user_id']);
var_dump($user->username());
var_dump($role);







