<?php

require 'BaseDatos.php';

session_start();

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}

$methodName = $_SERVER['REQUEST_METHOD'];

$productCreated = false;
if ($methodName === 'POST') {
    // Create the product
    $productName = $_POST["name"];
    $cost = $_POST["cost"];
    $price = $_POST["price"];
    $categoryId = $_POST["categoryId"];

    addProductToList($productName, $cost, $price, $categoryId);
    $productCreated = true;
}

// Aquí falla al crear nuevo producto
$productId = intval($_GET["productId"]);
//var_dump($productId);

$product = getProductById($productId);
var_dump($product);


?>

<?php

if (!$productCreated) { ?>

    <form method="post">
        <label for="id">ID:</label>
        <input type="number" id="id" name="id" value="<?php echo $product->productId() ?>"><br>
        <label for="category">Categoría:</label>
        <select name="categoryId">
            <?php
            $categoryList = showCategoryList();

            foreach ($categoryList as $category) {
                ?>
                <option value="<?= $category->categorytId() ?>"><?php echo $category->name() ?></option>

            <?php } ?>

        </select><br>
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name"><br>
        <label for="cost">Coste:</label>
        <input type="number" id="cost" name="cost"><br>
        <label for="price">Precio:</label>
        <input type="number" id="price" name="price"><br>

        <br>

        <button type="submit" name="add" value="add">Añadir</button>
    </form>


<?php } else {

    echo("Se ha creado el producto!");
}
?>




<br>

<a href="Articulos.php"><< Volver</a>

