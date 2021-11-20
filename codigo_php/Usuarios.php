<?php

include 'BaseDatos.php';

session_start();

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}

$userList = getUserList();

?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Último acceso</th>
        <th>Enabled</th>
        <th>Manejo</th>
    </tr>


    <?php
    foreach ($userList as $user) {
        ?>
        <tr>

            <td> <?php echo $user->id() ?></td>
            <td> <?php echo $user->username() ?></td>
            <td> <?php echo $user->email() ?></td>
            <td> <?php echo $user->lastAccess()->format('d/m/Y') ?></td>
            <td> <?php echo (int)$user->enabled() ?></td>
            <td>
                <a href="formUsuarios.php?operation=edit&userId=<?php echo $user->id() ?>">✏️</a>
                <a href="formUsuarios.php?operation=delete&userId=<?php echo $user->id() ?>">❌️</a>
            </td>
        </tr>

    <?php }; ?>

</table>
