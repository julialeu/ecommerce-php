<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
</head>

<!-- Creo el formulario html para que el usuario pueda
introducir su nombre de usuario e email -->
<body>

<?php

//definimos la variable para determinar si método POST o GET
$methodName = $_SERVER['REQUEST_METHOD'];

//si el método es post, se incluye el código de BaseDatos.php

/* definimos las variables $username y $email, su valor son los datos que
hemos escrito en el formulario y que se envían con el método POST al servidor */
if ($methodName === 'POST') {
    include 'BaseDatos.php';

    $username = $_POST["username"];
    $email = $_POST["email"];

/* creamos la variable $isValidAccess. Es booleano y su valor depende de la
llamada de la función login. Nos remitimos a la vista BaseDatos.php */

    $isValidAccess = login($username, $email);
}

?>

<form method="post" action="">
    <div class="form-element">
        <label>Usuario:</label>
        <input type="text" name="username"/>
    </div>
    <div class="form-element">
        <label>Email:</label>
        <input type="email" name="email"/>
    </div>
    <button type="submit" name="register" value="register">Acceder</button>
</form>

<?php
/*si la variable $isValidAccess está seteada y además es true,
damos la bienvenida al usuario logueado */

if (isset($isValidAccess) && $isValidAccess === true) {
    echo "<p>Bienvenid@ $username, pulsa <a href=\"Acceso.php\">AQUÍ</a> para continuar.</p>";

}

if (isset($isValidAccess) && $isValidAccess === false) {
    echo "<p>El usuario no existe.</p>";
}
?>

</body>
</html>