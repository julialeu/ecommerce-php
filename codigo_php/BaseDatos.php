<?php

/* Este fichero incluye los ficheros de las clases
que he creado de User, Product y Category para optimizar el código*/
include 'Entity/User.php';
include 'Entity/Product.php';
include 'Entity/Category.php';

// Si encontramos al usuario, esta función devuelve true

function login($username, $email)
{
 /* Si $user no es NULL se llama a la función session_start
    para iniciar la sesión */
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
    // Nos conectamos a la base de datos
    $DB = createConnectionDataBase("pac3_daw");

    // Definimos la consulta para obtener los datos de la tabla user.
    $sql = "SELECT * FROM user WHERE Email = '$email' AND FullName = '$username'";

    // Hacemos la consulta y guardamos el resultado en $result
    $result = mysqli_query($DB, $sql);

    if (mysqli_num_rows($result) > 0) {

        $row = $result->fetch_assoc();

        $id = $row['UserID'];
        $email = $row['Email'];
        $username = $row['FullName'];
        $enabled = (bool)$row['Enabled'];
        $lastAccess = new DateTime($row["LastAccess"]);


        $user = new User($id, $email, $username, $enabled, $lastAccess, '', false);

        return $user;
    }

    return null;
}
//esta es la función para crear la conexión a la base de datos.
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

/* esta función es para actualizar la fecha de último acceso
al que le pasamos por parámetro un objeto de la clase User. */
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

//determinamos el tipo de usuario con esta función
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

/*esta función devuelve un objeto de tipo User con todos los
datos recogidos de la consulta sql */
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
    $lastAccess = new DateTime($row["LastAccess"]);

    return new User($id, $email, $username, $enabled, $lastAccess, '', false);
}

/**
 * @return Product[]
 */
/*Función para obtener el listado de artículos.
Hacemos un inner join en el select para que se muestre el nombre de la categoría, no su ID. */
function getProductListData(int $pageNumber, string $orderBy): array
{
    $DB = createConnectionDataBase("pac3_daw");

    /* Definimos la constante NUM_PRODUCTS_PER_PAGE como propiedad de la clase Product.
    La seteamos a 10 para que se muestre 10 productos por página.
    La variable offset es para que la numeración continúe en cada pagina y no empiece
    siempre desde 1.*/

    $limit = Product::NUM_PRODUCTS_PER_PAGE;
    $offset = ($limit * $pageNumber) - $limit;

    $sql = 'SELECT product.*, category.Name as CategoryName
                FROM `product`
                left join category on product.CategoryID = category.CategoryID';

    /* Utilizamos estos IF's para ordenar en orden ascendente o
    descendente las columnas de la tabla artículos */
    if ($orderBy === 'IdAsc') {
        $sql = $sql . ' order by product.ProductID asc ';
    }

    if ($orderBy === 'IdDesc') {
        $sql = $sql . ' order by product.ProductID desc ';
    }

    if ($orderBy === 'CategoryAsc') {
        $sql = $sql . ' order by category.name asc ';
    }

    if ($orderBy === 'CategoryDesc') {
        $sql = $sql . ' order by category.name desc ';
    }

    if ($orderBy === 'NameAsc') {
        $sql = $sql . ' order by product.name asc ';
    }

    if ($orderBy === 'NameDesc') {
        $sql = $sql . ' order by product.name desc ';
    }

    if ($orderBy === 'CostAsc') {
        $sql = $sql . ' order by product.cost asc ';
    }

    if ($orderBy === 'CostDesc') {
        $sql = $sql . ' order by product.cost desc ';
    }

    if ($orderBy === 'PriceAsc') {
        $sql = $sql . ' order by product.price asc ';
    }

    if ($orderBy === 'PriceDesc') {
        $sql = $sql . ' order by product.price desc ';
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

function createProduct($productName, $cost, $price, $categoryId): void
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = 'INSERT INTO product (Name, Cost, Price, CategoryID)
            VALUES (\'' . $productName . '\',' . $cost . ',' . $price . ',' . $categoryId . ')';

    mysqli_query($DB, $sql);
}

function getProductById($productId): Product
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = "SELECT * FROM product WHERE ProductID = $productId";
    $result = mysqli_query($DB, $sql);
    $row = $result->fetch_assoc();

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

/**
 * @return User[]
 * @throws Exception
 */

/*Método para obtener la lista de usuarios */
function getUserList(string $orderBy): array
{
    $superAdminUserId = getSuperAdminId(); //variable para coger el ID de superAdmin
    $DB = createConnectionDataBase("pac3_daw");
    $sql = "SELECT * FROM user";

    if ($orderBy === 'NameAsc') {
        $sql = $sql . ' order by user.FullName asc ';
    }

    if ($orderBy === 'NameDesc') {
        $sql = $sql . ' order by user.FullName desc ';
    }

    $result = mysqli_query($DB, $sql);
    $users = [];

    foreach ($result as $row) {
        $id = (int) $row["UserID"];
        $username = $row["FullName"];
        $email = $row["Email"];
        $lastAccess = new DateTime($row["LastAccess"]);
        $enabled = $row["Enabled"];
        $isSuperAdmin = ($superAdminUserId === $id);

        $user = new User(
            $id,
            $email,
            $username,
            $enabled,
            $lastAccess,
            '',
            $isSuperAdmin
        );

        $users[] = $user;
    }

    return $users;
}

function editProduct(Product $productToEdit): void
{
    $productName = $productToEdit->name();
    $cost = $productToEdit->cost();
    $price = $productToEdit->price();
    $categoryId = $productToEdit->categoryId();
    $productId = $productToEdit->productId();

    $DB = createConnectionDataBase("pac3_daw");
    $sql = "UPDATE product SET Name = '$productName', Cost = $cost, Price = $price, CategoryID = $categoryId WHERE ProductID = $productId";
    mysqli_query($DB, $sql);
}

function deleteProduct(int $productId): void
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = "DELETE FROM product WHERE ProductID = $productId";
    mysqli_query($DB, $sql);
}

function createUser($email, $password, $username, bool $isEnabled): void
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = 'INSERT INTO user (Email, Password, FullName, Enabled, LastAccess)
            VALUES (\'' . $email . '\',\'' . $password . '\',\'' . $username . '\',' . (int)$isEnabled . ', \'' . date('Y-m-d H:i:s') . '\')';

    mysqli_query($DB, $sql);
}

//Método para saber si el usuario es superAdmin en la tabla de usuarios
function getSuperAdminId(): int
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = "SELECT * FROM setup";
    $result = mysqli_query($DB, $sql);
    $row = $result->fetch_assoc();
    $superAdmin = $row["SuperAdmin"];

    return (int) $superAdmin;
}

function editUser(User $userToEdit): void
{
    $username = $userToEdit->username();
    $email = $userToEdit->email();
    $password = $userToEdit->password();
    $enabled = (int)$userToEdit->enabled();
    $id = $userToEdit->id();

    $DB = createConnectionDataBase("pac3_daw");
    $sql = "UPDATE user SET Email = '$email',";
    if (!empty($password)) {
        $sql = $sql . " Password = '$password',";
    }
    $sql = $sql . " FullName = '$username', Enabled = $enabled  WHERE UserID = $id";

    mysqli_query($DB, $sql);
}

function deleteUser(int $id): void
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = "DELETE FROM user WHERE UserID = $id";
    $result = mysqli_query($DB, $sql);
}

function getUserById($id): User
{
    $DB = createConnectionDataBase("pac3_daw");
    $sql = "SELECT * FROM user WHERE UserID = $id";
    $result = mysqli_query($DB, $sql);

    $row = $result->fetch_assoc();

    $id = $row["UserID"];
    $userName = $row["FullName"];
    $email = $row["Email"];
    $lastAccess = new DateTime($row["LastAccess"]);
    $enabled = $row["Enabled"];

    return new User(
        $id,
        $email,
        $userName,
        $enabled,
        $lastAccess,
        '',
        ''
    );
}
