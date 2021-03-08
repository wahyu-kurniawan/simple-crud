<?php


$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
$title = '';
// $image = '';
// $description = '';
$price = '';

function randomString($n)
{
    $characters = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
};
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = $_FILES['image'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');

    // var_dump($_POST);
    // exit;
    // var_dump($_FILES)['image'];
    // exit;

    if (!$title) {
        $errors[] = 'Title is required';
    }
    if (!$description) {
        $errors[] = 'Description is required';
    }
    if (!$image) {
        $errors[] = 'Image is required';
    }
    if (!$price) {
        $errors[] = 'Price is required';
    }

    if (!is_dir('images')) {
        mkdir('images');
    }

    if (empty($errors)) {
        $image = $_FILES['image'] ?? null;

        if ($image) {
            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }
        $statement = $pdo->prepare("INSERT INTO products (title,description,image,price,create_date)

                            VALUES (:title,:description,:image,:price,:date)
");
        $statement->bindValue(':title', $title);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':date', $date);
        $statement->execute();
    }
    header('Location:index.php');
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>Products Crud</title>
</head>

<body>
    <h1>Products Crud</h1>
    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error) : ?>
                <div><?= $error; ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="container my-4">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Title</label>
                <input type="text" name="title" value="<?= $title; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Product Description</label>
                <textarea name="description" value="<?= $description; ?>" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" value="<?= $image; ?>" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Product Price</label>
                <input type="number" name="price" step="0.1" value="<?= $price; ?>" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>

</html>