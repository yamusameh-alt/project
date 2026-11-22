<?php
// Start session if it hasn't been started already so we can check if the user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config/config.php';

$result = $conn->query(
"SELECT * FROM products LIMIT 3"
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>DreamHome Furniture</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">

    <div class="hero">
        <h1>High-Quality Furniture For Every Home</h1>
        <p>
            Discover stylish and comfortable furniture
            for every room in your home.
        </p>
        <a href="products.php" class="btn">Shop Now</a>
    </div>

    <h2>Featured Products</h2>

    <div class="products-grid">

        <?php while($product = $result->fetch_assoc()): ?>

        <div class="product">

            <?php if(!empty($product['image'])): ?>
                <img src="assets/images/<?= $product['image']; ?>" alt="<?= $product['name']; ?>" class="product-image">
            <?php endif; ?>

            <h2><?= $product['name']; ?></h2>

            <p><?= $product['description']; ?></p>

            <p><strong>Price:</strong> $<?= $product['price']; ?></p>

           <div class="product-actions" style="margin-top: 15px;">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="add_to_cart.php?id=<?= $product['product_id']; ?>" class="btn">Add to Cart</a>
                <?php else: ?>
                    <button onclick="addToGuestCart(<?= $product['product_id']; ?>)" class="btn" style="cursor: pointer;">Add to Cart</button>
                <?php endif; ?>
            </div>
            ```


            </div>

        <?php endwhile; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

<script>
function addToGuestCart(productId) {
    // 1. Get the current guest cart array from the browser's storage, or create a clean one if empty
    let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];

    // 2. Check if this product is already saved inside the array
    let existingItem = cart.find(item => item.product_id === productId);

    if (existingItem) {
        existingItem.quantity += 1; // Increment count
    } else {
        cart.push({ product_id: productId, quantity: 1 }); // Append fresh item object
    }

    // 3. Save the updated array structural string right back into storage memory
    localStorage.setItem('guest_cart', JSON.stringify(cart));
    
    alert('Item added to temporary guest cart!');
}
</script>

</body>
</html>