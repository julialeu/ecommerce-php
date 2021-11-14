<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>
<body>

<?php

$methodName = $_SERVER['REQUEST_METHOD'];

if ($methodName === 'POST') {
    include 'BaseDatos.php';

    $username = $_POST["username"];
    $email = $_POST["email"];

    $isValidAccess = login($username, $email);
}

?>

<form method="post" action="" name="signup-form">
    <div class="form-element">
        <label>Usuario:</label>
        <input type="text" name="username" value="Terry Hatchet" required/>
    </div>
    <div class="form-element">
        <label>Email:</label>
        <input type="email" name="email" required value="Terry@linux.com"/>
    </div>
    <button type="submit" name="register" value="register">Acceder</button>
</form>

<?php
if (isset($isValidAccess) && $isValidAccess === true) {
    echo "<p>Bienvenid@ $username, pulsa <a href=\"Acceso.php\">AQUÍ</a> para continuar.</p>";

}

if (isset($isValidAccess) && $isValidAccess === false) {
    echo "<p>El usuario no existe.</p>";
}
?>


</body>
</html>