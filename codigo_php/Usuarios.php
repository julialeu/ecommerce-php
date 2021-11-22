<?php

include 'BaseDatos.php';

session_start();

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}


echo "<a href=\"formUsuarios.php?operation=create\">
    Crear nuevo usuario</a>" . "<br><br>";

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
            <td <?php if($user->isSuperAdmin()) { ?>
                style="color:red" <?php } ?>
            > <?php echo $user->id() ?></td>

            <td <?php if($user->isSuperAdmin()) { ?>
                style="color:red" <?php } ?>

            > <?php echo $user->username() ?>
            </td>
            <td <?php if($user->isSuperAdmin()) { ?>
                style="color:red" <?php } ?>

            > <?php echo $user->email() ?></td>
            <td <?php if($user->isSuperAdmin()) { ?>
                style="color:red" <?php } ?>

            > <?php echo $user->lastAccess()->format('d/m/Y') ?></td>
            <td <?php if($user->isSuperAdmin()) { ?>
                style="color:red" <?php } ?>

            > <?php echo (int)$user->enabled() ?></td>
            <td>
                <a <?php if($user->isSuperAdmin()) { ?>
                        href=""
                        <?php } ?> else <?php { ?>

                        href="formUsuarios.php?operation=edit&userId=<?php echo $user->id() ?> <?php } ?>">✏️</a>
                <a <?php if($user->isSuperAdmin()) { ?>
                        href=""
                        <?php } ?> else <?php { ?>
                        href="formUsuarios.php?operation=delete&userId=<?php echo $user->id() ?> <?php } ?>">❌️</a>
            </td>
        </tr>

    <?php }; ?>

</table>

