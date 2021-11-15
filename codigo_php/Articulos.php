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

if (isset($_GET['orderBy'])) {
    // Puede ser: CategoryAsc, CategoryDesc
    $orderBy = $_GET['orderBy'];
} else {
    $orderBy = '';
}

$productList = getProductListData($pageNumber, $orderBy);

?>

<?php
$user = getUserFromSession();

$role = getRol($user);

if ($role === User::ROLE_SUPERADMIN || $role === User::ROLE_AUTHORIZED) {

    echo "<a href=\"formArticulos.php\">
    Crear nuevo producto</a>" . "<br>";

}

if (empty($orderBy)) {
    $categoryUrl = "Articulos.php?page=$pageNumber&orderBy=CategoryAsc";
} else {
    $categoryUrl = "Articulos.php?page=$pageNumber&orderBy=CategoryDesc";
}

// TODO: Fill in this URL
$nameUrl = '';
    ?>

<table>
    <tr>
        <th>ID</th>
        <th><a href="<?php echo($categoryUrl); ?>">Categoría</a></th>
        <th><a href="<?php echo($nameUrl); ?>">Nombre</th>
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

if ($pageNumber > 1) {

    $previousUrl = '?page=' . ($pageNumber - 1);

    if (!empty($orderBy)) {
        $previousUrl = $previousUrl . '&orderBy=' . $orderBy;
    }
    ?>

    <a href="<?php echo $previousUrl ?>">
        Anterior </a>
<?php } ?>

<?php
$numTotalProducts = numTotalProducts();
$lastPageNumber = ceil($numTotalProducts / Product::NUM_PRODUCTS_PER_PAGE);


if ($pageNumber < $lastPageNumber) {

    $nextUrl = '?page=' . ($pageNumber + 1);

    if (!empty($orderBy)) {
        $nextUrl = $nextUrl . '&orderBy=' . $orderBy;
    }
    ?>

    <a href="<?php echo $nextUrl ?>">
        Siguiente</a>

<?php } ?>
