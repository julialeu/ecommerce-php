<?php

require 'BaseDatos.php';

session_start();

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}

$operation = $_GET['operation'];

if ($operation === 'create') {
    echo ('<h1>Crear producto</h1>');
}

if ($operation === 'edit') {
    echo ('<h1>Editar producto</h1>');
}

if ($operation === 'delete') {
    echo ('<h1>Eliminar producto</h1>');
}

var_dump($operation);

$methodName = $_SERVER['REQUEST_METHOD'];

echo ($methodName);

$productCreated = false;

echo ('debug 1');
var_dump($methodName);
if ($methodName === 'POST') {
    echo ('debug 2');
    if ($operation === 'create') {
        echo ('debug 3');
        // Create the product
        $productName = $_POST["name"];
        $cost = $_POST["cost"];
        $price = $_POST["price"];
        $categoryId = $_POST["categoryId"];

        createProduct($productName, $cost, $price, $categoryId);
        $productCreated = true;
    }
}

if ($methodName === 'GET' && $operation === 'edit') {
    $productId = intval($_GET["productId"]);
    $product = getProductById($productId);

}

?>

<?php
//var_dump($product);

if (!$productCreated) { ?>

<form method="post">
    <label for="id">ID:</label>
    <input type="number" id="id" name="id" value="<?php if (isset($product)) { echo $product->productId(); } ?>"><br>
    <label for="category">Categoría:</label>
    <select name="categoryId">
        <?php
        $categoryList = showCategoryList();

        foreach ($categoryList as $category) {
            ?>
            <option <?php if (isset($product) && $product->categoryId() === $category->categorytId()) { echo 'selected="selected"'; } ?> value="<?= $category->categorytId() ?>"><?php echo $category->name() ?></option>

        <?php } ?>

    </select>
    <br>
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name" value="<?php if (isset($product)) { echo $product->name(); } ?>"><br>
    <label for="cost" >Coste:</label>
    <input type="number" id="cost" name="cost" value="<?php if (isset($product)) { echo $product->cost(); } ?>"><br>
    <label for="price">Precio:</label>
    <input type="number" id="price" name="price" value="<?php if (isset($product)) { echo $product->price(); } ?>"><br>

    <br>

    <button type="submit" name="add" value="add">

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

<br>

<a href="Articulos.php"><< Volver</a>


