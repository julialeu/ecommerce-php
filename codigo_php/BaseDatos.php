<?php

include 'Entity/User.php';
include 'Entity/Product.php';


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
function getProductListData(int $pageNumber): array
{
    $limit = Product::NUM_PRODUCTS_PER_PAGE;
    $offset = ($limit * $pageNumber) - $limit;

    $sql = "SELECT product.*, category.Name as CategoryName
                FROM `product`
                left join category on product.CategoryID = category.CategoryID
                limit " .  $limit . "
                OFFSET " . $offset;

    $DB = createConnectionDataBase("pac3_daw");
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

