<?php

require 'BaseDatos.php';

//Mantenemos la sesión activa
session_start();

//Este formulario sigue la misma estructura de funcionamiento que el formulario de artículos.
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}

$operation = $_GET['operation'];

switch ($operation) {
    case 'create':
        echo('<h1>Crear usuario</h1>');
        break;
    case 'edit':
        echo('<h1>Editar usuario</h1>');
        break;
    case 'delete':
        echo('<h1>Eliminar usuario</h1>');
        break;
}
$methodName = $_SERVER['REQUEST_METHOD'];

$userCreated = false;
$userEdited = false;
$userDeleted = false;

if ($methodName === 'POST') {

    if ($operation === 'create') {
        // Create the user
        $userName = $_POST["name"];
        $password = $_POST["pwd"];
        $email = $_POST["email"];
        $isEnabled = $_POST["isEnabled"];

        createUser($email, $password, $userName, $isEnabled);
        $userCreated = true;
    }

    if ($operation === 'edit') {
        // Edit the user
        $userName = $_POST["name"];
        $password = $_POST["pwd"];
        $email = $_POST["email"];
        $lastAccess = $_POST["lastAccess"];
        $enabled = $_POST["isEnabled"];
        $id = $_POST["id"];

        $userToEdit = new User(
            $id,
            $email,
            $userName,
            $enabled,
            new DateTime($lastAccess),
            $password,
            ''
        );
        editUser($userToEdit);
        $userEdited = true;
    }

    if ($operation === 'delete') {
        // Delete the user
        $id = $_POST["id"];
        deleteUser($id);
        $userDeleted = true;
    }
}

if ($methodName === 'GET' && ($operation === 'edit' || $operation === 'delete')) {
    $userId = intval($_GET["userId"]);
    $user = getUserById($userId);
}

?>

<html>

<body>

<?php

if ($methodName === 'GET') { ?>

    <form method="POST">
        <?php if (in_array($operation, ['delete', 'edit'], true)) { ?>

            <label for="id">ID:</label><br>
            <input
                    type="number"
                    id="id"
                    name="id"
                    readonly="readonly"
                    value="<?php if (isset($user)) {
                        echo $user->id();
                    } ?>"
            >
        <?php } ?>
        <br>

        <label for="name">Nombre:</label><br>
        <input
                type="text"
                id="name"
                name="name"
                value="<?php if (isset($user)) {
                    echo $user->username();
                } ?>"
        >
        <br>

        <label for="pwd">Contraseña:</label><br>
        <input
                type="password"
                id="pwd"
                name="pwd"
        >
        <br>

        <label for="email">Correo:</label><br>
        <input
                type="email"
                id="email"
                name="email"
                value="<?php if (isset($user)) {
                    echo $user->email();
                } ?>"
        >
        <br>

        <?php if (in_array($operation, ['delete', 'edit'], true)) { ?>
        <label for="lastAccess">Último acceso:</label><br>
        <input
                type="date"
                id="lastAccess"
                name="lastAccess"
                readonly="readonly"
                value="<?php if (isset($user)) {
                    echo $user->lastAccess()->format('Y-m-d');
                } ?>"

        >
        <?php }?>
        <br>

        <p>Autorizado:</p>
        <label for="isEnabled">Si</label>
        <input type="radio" name="isEnabled" value="1" checked="true"><br>
        <label for="isEnabled">No</label>
        <input type="radio" name="isEnabled" value="0"><br>

        <button type="submit" name="add">
            <?php if ($operation === 'create') { ?>
                Añadir
            <?php } ?>

            <?php if ($operation === 'edit') { ?>
                Editar
            <?php } ?>

            <?php if ($operation === 'delete') { ?>
                Eliminar
            <?php } ?>

        </button>
    </form>

<?php } ?>

<?php if ($userCreated) {
    echo('Usuario creado!');
}

if ($userEdited) {
    echo('Usuario editado!');
}

if ($userDeleted) {
    echo('Usuario eliminado!');
}

?>

<a href="Usuarios.php">Volver</a>

</body>
</html>