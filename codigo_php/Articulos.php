<?php

include 'BaseDatos.php';

session_start();

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: /pac_desarrollo_servidor/codigo_php/Index.php');
    exit();
}

if (isset($_GET['page'])) {
    $pageNumber = (int)$_GET['page'];
} else {
    $pageNumber = 1;
}

if (isset($_GET['orderBy'])) {
    // Puede ser: CategoryAsc, CategoryDesc, CostAsc...
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


    echo "<a href=\"formArticulos.php?operation=create\">
    Crear nuevo producto</a>" . "<br><br>";

}

if ($orderBy === 'IdAsc') {
    $idUrl = "Articulos.php?page=1&orderBy=IdDesc";
} else {
    $idUrl = "Articulos.php?page=1&orderBy=IdAsc";
}

if ($orderBy === 'CategoryAsc') {
    $categoryUrl = "Articulos.php?page=1&orderBy=CategoryDesc";
} else {
    $categoryUrl = "Articulos.php?page=1&orderBy=CategoryAsc";
}

if ($orderBy === 'NameAsc') {
    $nameUrl = "Articulos.php?page=1&orderBy=NameDesc";
} else {
    $nameUrl = "Articulos.php?page=1&orderBy=NameAsc";
}

if ($orderBy === 'PriceAsc') {
    $priceUrl = "Articulos.php?page=1&orderBy=PriceDesc";
} else {
    $priceUrl = "Articulos.php?page=1&orderBy=PriceAsc";
}

if ($orderBy === 'CostAsc') {
    $costUrl = "Articulos.php?page=1&orderBy=CostDesc";
} else {
    $costUrl = "Articulos.php?page=1&orderBy=CostAsc";
}


?>

<table>
    <tr>
        <th><a href="<?php echo($idUrl); ?>">ID</a></th>
        <th><a href="<?php echo($categoryUrl); ?>">Categoría</a></th>
        <th><a href="<?php echo($nameUrl); ?>">Nombre</th>

        <? if (in_array($role, [User::ROLE_SUPERADMIN, User::ROLE_AUTHORIZED])) { ?>
            <th><a href="<?php echo($costUrl); ?>">Coste</a></th>
        <?php } ?>

        <th><a href="<?php echo($priceUrl); ?>">Precio</a></th>
        <? if ($role === User::ROLE_SUPERADMIN) { ?>
            <th>Manejo</th>
        <?php } ?>

        <style>
            .right {
                text-align: right;
            }
        </style>
    </tr>

    <?php

    foreach ($productList as $product) {
        ?>
        <tr>
            <td> <?php echo $product->productId() ?></td>
            <td> <?php echo $product->categoryName() ?></td>
            <td> <?php echo $product->name() ?></td>
            <? if (in_array($role, [User::ROLE_SUPERADMIN, User::ROLE_AUTHORIZED])) { ?>
                <td class="right"> <?php echo $product->cost() ?></td>
            <?php } ?>

            <td class="right"> <?php echo $product->price() ?></td>
            <? if ($role === User::ROLE_SUPERADMIN) { ?>
                <td>
                    <a href="formArticulos.php?operation=edit&productId=<?php echo $product->productId() ?>">✏️</a>
                    &nbsp<a href="formArticulos.php?operation=delete&productId=<?php echo $product->productId() ?>">❌</a>
                </td>
            <?php } ?>
        </tr>
        <?php
    };
    ?>

</table>

<br>

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
