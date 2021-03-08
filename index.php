<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$search = $_GET['search'] ?? '';

if ($search) {
    $statement = $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY create_date');
    $statement->bindValue(':title', "%$search%");
} else {
    $statement = $pdo->prepare('SELECT * FROM products ORDER BY create_date');
}
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Products Crud</title>
</head>

<body>
    <div class="container">
        <h1>Products Crud</h1>
        <form>
            <div class="input-group mb-2">
                <input type="text" class="form-control" placeholder="Search product" name="search" value="<?= $search ?>">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </form>
        <a href="create.php" class="btn btn-outline-primary">Create Products</a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Titel</th>
                    <th scope="col">Image</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Create Date</th>
                    <th scope="col">Action</th>


                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($products as $i => $product) : ?>
                        <th scope="row"><?= $i + 1 ?></th>
                        <td><?= $product['title']; ?></td>
                        <td><img src="<?= $product['image']; ?>" class="thumb-image"></td>
                        <td><?= $product['description']; ?></td>
                        <td><?= $product['price']; ?></td>
                        <td><?= $product['create_date']; ?></td>
                        <td>
                            <a href="update.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form style="display:inline" action="delete.php" method="post">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>