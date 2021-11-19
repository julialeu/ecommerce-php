<?php

include 'Entity/User.php';
include 'Entity/Product.php';
include 'Entity/Category.php';


function login($username, $email)
{
    $user = findUser($username, $email);
    if ($user !== null) {
        session_start();
        updateLastUserAccess($user);

        $_SESSION['user_id'] = $user->id();

        return true;
    }

    return false;
}

function findUser($username, $email): ?User
{
    // Nos conectamos a la base de datos world
    $DB = createConnectionDataBase("pac3_daw");

    // Definimos la consulta para obtener todos los datos de la tabla user.
    $sql = "SELECT * FROM user WHERE Email = '$email' AND FullName = '$username'";

    // Hacemos la consulta y guardamos el resultado en $result
    $result = mysqli_query($DB, $sql);

    if (mysqli_num_rows($result) > 0) {

        $row = $result->fetch_assoc();

        $id = $row['UserID'];
        $email = $row['Email'];
        $username = $row['FullName'];
        $enabled = (bool)$row['Enabled'];

        $user = new User($id, $email, $username, $enabled);

        return $user;
    }

    return null;
}

function createConnectionDataBase($database)
{

    // Datos de conexión
    $host = "localhost";
    $user = "root";
    $password = "";

    // Establecemos la conexión con la base de datos
    $conexion = mysqli_connect($host, $user, $password, $database);

    // Si hay un error en la conexión, lo mostramos y detenemos.
    if (!$conexion) {
        die("<br>Error de conexión con la base de datos: " . mysqli_connect_error());
    }

    return $conexion;
}

function updateLastUserAccess(User $user): void
{
    $DB = createConnectionDataBase("pac3_daw");

    $lastAccessDate = date("Y-m-d");
    $userId = $user->id();
    $sql = "UPDATE user SET LastAccess = '$lastAccessDate' WHERE UserID = $userId";

    mysqli_query($DB, $sql);
}

/**
 * @param User $user
 * @return string Returns the role of the user
 */
function getRol(User $user): string
{
    $DB = createConnectionDataBase("pac3_daw");
    $userId = $user->id();
    $sql = "SELECT * FROM setup WHERE SuperAdmin = '$userId'";
    $result = mysqli_query($DB, $sql);

    if (mysqli_num_rows($result) > 0) {
        return User::ROLE_SUPERADMIN;
    }

    if ($user->enabled() === true) {
        return User::ROLE_AUTHORIZED;
    }

    return User::ROLE_REGISTERED;
}


function getUserFromSession(): User
{
    $userId = $_SESSION['user_id'];

    $DB = createConnectionDataBase("pac3_daw");
    $sql = "SELECT * FROM user WHERE UserID = $userId";
    $result = mysqli_query($DB, $sql);

    if (mysqli_num_rows($result) === 0) {
        throw new \Exception('User with id ' . $userId . ' not found');
    }
    $row = $result->fetch_assoc();

    $id = $row['UserID'];
    $email = $row['Email'];
    $username = $row['FullName'];
    $enabled = (bool)$row['Enabled'];

    return new User($id, $email, $username, $enabled);
}

/**
 * @return Product[]
 */
function getProductListData(int $pageNumber, string $orderBy): array
{
    $DB = createConnectionDataBase("pac3_daw");
    $limit = Product::NUM_PRODUCTS_PER_PAGE;
    $offset = ($limit * $pageNumber) - $limit;

    $sql = 'SELECT product.*, category.Name as CategoryName
                FROM `product`
                left join category on product.CategoryID = category.CategoryID';

    if ($orderBy === 'CategoryAsc') {
        $sql = $sql . ' order by category.name asc ';
    }

    if ($orderBy === 'CategoryDesc') {
        $sql = $sql . ' order by category.name desc ';

    }

    if ($orderBy === 'NameAsc') {
        $sql = $sql . ' order by product.name asc ';
    }

    $sql = $sql . " limit " . $limit . "
                OFFSET " . $offset;

    $result = mysqli_query($DB, $sql);
    $products = [];

    foreach ($result as $row) {

        $productId = $row["ProductID"];
        $name = $row["Name"];
        $cost = $row["Cost"];
        $price = $row["Price"];
        $categoryId = $row["CategoryID"];
        $categoryName = $row["CategoryName"];

        $product = new Product(
            $productId,
            $name,
            $cost,
            $price,
            $categoryId,
            $categoryName
        );

        $products[] = $product;
    }

    return $products;
}

function numTotalProducts(): int
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = "SELECT COUNT(ProductID) as numTotalProducts FROM product";
    $result = mysqli_query($DB, $sql);
    $row = $result->fetch_assoc();

    return (int)$row['numTotalProducts'];
}

/**
 * @return Category[]
 */
function showCategoryList(): array
{

    $DB = createConnectionDataBase("pac3_daw");
    $sql = 'SELECT * 
                FROM `category`';
    $result = mysqli_query($DB, $sql);
    $categories = [];

    foreach ($result as $row) {

        $categoryId = $row["CategoryID"];
        $name = $row["Name"];

        $category = new Category(
            $categoryId,
            $name
        );

        $categories[] = $category;
    }

    return $categories;
}

function addProductToList($productName, $cost, $price, $categoryId): void
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = 'INSERT INTO product (Name, Cost, Price, CategoryID)
            VALUES (\'' . $productName . '\',' . $cost . ',' . $price . ',' . $categoryId . ')';

    $result = mysqli_query($DB, $sql);
}

function updateDataProduct ($productId, $productName, $cost, $price, $categoryId):void
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = 'UPDATE product SET Name = "$productName", Cost = $cost, Price = $price, CategoryID = $categoryId WHERE ProductID = $productId;';
}

// UPDATE product SET Name = "rebeca", Cost = 20.25, Price = 40, CategoryID = 2 WHERE ProductID = 55;

function getProductById($productId): Product
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = "SELECT * FROM product WHERE ProductID = $productId";
    $result = mysqli_query($DB, $sql);
//    var_dump($sql);
//    var_dump($result);

    $row = $result->fetch_assoc();
//    var_dump($row);

    $productId = $row["ProductID"];
    $name = $row["Name"];
    $cost = $row["Cost"];
    $price = $row["Price"];
    $categoryId = $row["CategoryID"];
    $categoryName = '';

    return new Product(
        $productId,
        $name,
        $cost,
        $price,
        $categoryId,
        $categoryName
    );
}