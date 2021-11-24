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
        echo('<h1>Crear producto</h1>');
        break;
    case 'edit':
        echo('<h1>Editar producto</h1>');
        break;
    case 'delete':
        echo('<h1>Eliminar producto</h1>');
        break;
}

$methodName = $_SERVER['REQUEST_METHOD'];

$productCreated = false;
$productEdited = false;
$productDeleted = false;


if ($methodName === 'POST') {
    if ($operation === 'create') {
        // Create the product
        $productName = $_POST["name"];
        $cost = $_POST["cost"];
        $price = $_POST["price"];
        $categoryId = $_POST["categoryId"];

        createProduct($productName, $cost, $price, $categoryId);
        $productCreated = true;
    }

    if ($operation === 'edit') {
        // Edit the product
        $productId = $_POST["productId"];
        $productName = $_POST["name"];
        $cost = $_POST["cost"];
        $price = $_POST["price"];
        $categoryId = $_POST["categoryId"];

        $productToEdit = new Product(
            $productId,
            $productName,
            $cost,
            $price,
            $categoryId,
            ''
        );

        editProduct($productToEdit);
        $productEdited = true;
    }

    if ($operation === 'delete') {
        // Delete the product
        $productId = $_POST["productId"];
        deleteProduct($productId);
        $productDeleted = true;
    }
}

if ($methodName === 'GET' && ($operation === 'edit' || $operation === 'delete')) {
    $productId = intval($_GET["productId"]);
    $product = getProductById($productId);
}

?>

<?php

if ($methodName === 'GET') { ?>

    <form method="post">
        <?php if (in_array($operation, ['delete', 'edit'], true)) { ?>
            <label for="id">ID:</label>
            <input
                    type="number"
                    id="id"
                    name="productId"
                    readonly="readonly"
                    value="<?php if (isset($product)) { echo $product->productId(); } ?>"
            >
        <?php } ?>
        <br>

        <label for="category">Categoría:</label>
        <select name="categoryId">
            <?php
            $categoryList = showCategoryList();

            foreach ($categoryList as $category) {
                ?>
                <option
                    <?php
                    if ($operation === 'delete') { ?>
                        disabled
                    <?php } ?>

                    <?php if (isset($product) && $product->categoryId() === $category->categorytId()) {
                    echo 'selected="selected"';
                } ?> value="<?= $category->categorytId() ?>"><?php echo $category->name() ?></option>

            <?php } ?>

        </select>
        <br>
        <label for="name">Nombre:</label>
        <input
                type="text"
                id="name"
                name="name"
                value="<?php if (isset($product)) {
                    echo $product->name();
                } ?>"
            <?php if ($operation === 'delete') { ?>
                readonly="readonly"
            <?php } ?>
        >
        <br>
        <label for="cost">Coste:</label>
        <input
                id="cost"
                name="cost"
                value="<?php if (isset($product)) { echo $product->cost();} ?>"
                <?php if ($operation === 'delete') { ?>
                    readonly="readonly"
               <?php } ?>
        >
        <br>

        <label for="price">Precio:</label>
        <input
                id="price"
                name="price"
                value="<?php if (isset($product)) { echo $product->price(); } ?>"
                <?php if ($operation === 'delete') { ?>
                    readonly="readonly"
                <?php } ?>
        >
        <br>

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

<?php } ?>


<?php if ($productCreated) {
    echo('Producto creado!');
}

if ($productEdited) {
    echo('Producto editado!');
}

if ($productDeleted) {
    echo('Producto eliminado!');
}

?>

<br>
<br>
<br>

<a href="Articulos.php">Volver</a>


