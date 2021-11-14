<?php

include 'BaseDatos.php';

session_start();

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}

// TODO: si no llega el parámetro page, poner pageNumber a 1
if (isset($_GET['page'])) {
    $pageNumber = (int)$_GET['page'];
} else {
    $pageNumber = 1;
}

$productList = getProductListData($pageNumber);

?>

<table>
    <tr>
        <th>ID</th>
        <th>Categoría</th>
        <th>Nombre</th>
        <th>Coste</th>
        <th>Precio</th>
        <th>Manejo</th>
    </tr>

    <?php

    foreach ($productList as $product) {
        ?>
        <tr>
            <td> <?php echo $product->productId() ?></td>
            <td> <?php echo $product->categoryName() ?></td>
            <td> <?php echo $product->name() ?></td>
            <td> <?php echo $product->cost() ?></td>
            <td> <?php echo $product->price() ?></td>
            <td> Lápiz, cruz</td>
        </tr>
        <?php
    };
    ?>

</table>


<?php

if ($pageNumber > 1) { ?>
    <a href="/pac_desarrollo_servidor/codigo_php/Articulos.php?page=<?php echo($pageNumber - 1) ?>">
        Anterior </a>
<?php } ?>

<?php
$numTotalProducts = numTotalProducts();
$lastPageNumber = ceil($numTotalProducts / Product::NUM_PRODUCTS_PER_PAGE);


if ($pageNumber < $lastPageNumber) { ?>

    <a href="/pac_desarrollo_servidor/codigo_php/Articulos.php?page=<?php echo($pageNumber + 1) ?>">
        Siguiente</a>

<?php } ?>
