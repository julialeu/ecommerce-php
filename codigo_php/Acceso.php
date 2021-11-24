<?php

/* escribimmos la función session_start() para
mantener la sesión activa*/

session_start();
//Si no hay una sesión valida, remitimos al usuario a la pantalla de inicio
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}

include 'BaseDatos.php';

/* la variable $user toma los valores devueltos por la función
getUserFromSession() */
$user = getUserFromSession();

//creamos una variable para definir el tipo de rol
$role = getRol($user);

//los 3 tipos de rol pueden acceder al enlace de Articulos.php
if($role === User::ROLE_SUPERADMIN || $role === User::ROLE_AUTHORIZED || user::ROLE_REGISTERED ) {

    echo "<a href=\"Articulos.php\">Artículos</a>"."<br>";

}

//solo el rol superAdmin puede acceder al enlace Usuarios
if($role === User::ROLE_SUPERADMIN){

    echo "<a href=\"Usuarios.php\">Usuarios</a>"."<br>";
}

    echo "<a href=\"Index.php\">Volver</a>"."<br>";

