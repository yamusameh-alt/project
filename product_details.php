<?php

include 'config/config.php';

if(!isset($_GET['id'])){
    die("Product not found");
}

$id = $_GET['id'];

$stmt = $conn->prepare(
"SELECT p.*, c.name AS category_name
 FROM products p
 LEFT JOIN categories c
 ON p.category_id = c.category_id
 WHERE p.product_id=?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

if(!$product){
    die("Product not found");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $product['name']; ?></title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">

    <div class="product-details">

        <img
        src="assets/images/<?= $product['image']; ?>"
        alt="<?= $product['name']; ?>"
        class="product-details-image">

        <h1><?= $product['name']; ?></h1>

        <p>
            <strong>Category:</strong>
            <?= $product['category_name']; ?>
        </p>

        <p>
            <?= $product['description']; ?>
        </p>

        <p class="price">
            <strong>Price:</strong>
            $<?= $product['price']; ?>
        </p>

        <p>
            <strong>Stock Available:</strong>
            <?= $product['stock_quantity']; ?>
        </p>

        <a href="add_to_cart.php?id=<?= $product['product_id']; ?>">
            <button>Add To Cart</button>
        </a>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>