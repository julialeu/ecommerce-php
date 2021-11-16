<?php

include 'BaseDatos.php';

session_start();

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}

$categoryList = showCategoryList();

?>

<form>
    <label for="id">ID:</label>
    <input type="number" id="id" name="id"><br>
    <label for="category">Categoría:</label>
    <select name="category">
        <?php

        foreach ($categoryList as $category) {
            $hola = '';
            $hola;
            ?>
            <option value="<?= $category->categorytId()?>"><?php echo $category->name() ?></option>

        <?php } ?>

    </select><br>
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name"><br>
    <label for="cost">Coste:</label>
    <input type="number" id="cost" name="cost"><br>
    <label for="price">Precio:</label>
    <input type="number" id="price" name="price"><br>

    <br>

    <button type="submit" name="back" value="back">Volver</button>
    <button type="submit" name="add" value="add">Añadir</button>
</form>

