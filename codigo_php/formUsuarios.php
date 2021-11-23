<?php

require 'BaseDatos.php';

session_start();

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
echo('Método: ' . $methodName . '<br><br>');

$userCreated = false;
$userEdited = false;
$userDeleted = false;

if ($methodName === 'POST') {

    if ($operation === 'create') {
        // Create the user
        $userName = $_POST["name"];
        $password = $_POST["pwd"];
        $email = $_POST["email"];
        $lastAccess = $_POST["lastAccess"];
        $enabled = $_POST["enabled"];

        createUser($email, $password, $userName, $enabled);
        $userCreated = true;
    }

    if ($operation === 'edit') {
        // Edit the user
        $userName = $_POST["name"];
        $password = $_POST["pwd"];
        $email = $_POST["email"];
        $lastAccess = $_POST["lastAccess"];
        $enabled = $_POST["enabled"];
        $id = $_POST["id"];
        $isSuperAdmin = $_POST["isSuperAdmin"];

        $userToEdit = new User(
                $id,
                $email,
                $userName,
                $enabled,
                $lastAccess,
                '',
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
    $id = intval($_GET["id"]);
    $user = getUserById($id);
}

?>

<?php

if ($methodName === 'GET') { ?>


<html>

<body>

<h2>Tipo de acción</h2>

<form method="POST">

    <?php if (in_array($operation, ['delete', 'edit'], true)) { ?>
    <label for="id">ID:</label><br>
    <input type="number" id="id" name="id" readonly="readonly" value="<?php if (isset($id)) {
        echo $user->id();
        } ?>"><br>
    <?php } ?>

    <label for="name">Nombre:</label><br>
    <input type="text" id="name" name="name"><br>
    <label for="pwd">Contraseña:</label><br>
    <input type="password" id="pwd" name="pwd"><br>
    <label for="email">Correo:</label><br>
    <input type="email" id="email" name="email"><br>
    <label for="lastAccess">Último acceso:</label><br>
    <input type="date" id="lastAccess" name="lastAccess"><br>
    <p>Autorizado:</p>
    <input type="radio" id="authorized" name="enabled">
    <label for="authorized">Si</label><br>
    <input type="radio" id="notAuthorized" name="enabled">
    <label for="notAuthorized">No</label><br><br>

</form>
</body>
<button type="submit" name="add">Añadir</button>
<a href="Usuarios.php">Volver</a>

</html>

