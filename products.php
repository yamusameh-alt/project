<?php
// Start session if it hasn't been started already so we can check if the user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config/config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Furniture Products</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">

<h1>Our Furniture Collection</h1>

<div class="products-grid">

<?php
// 1. Check if a search query exists in the URL
$search_query = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = $conn->real_escape_string(trim($_GET['search']));
}

// 2. Build the SQL statement based on whether there is a search filter
if (!empty($search_query)) {
    // Filters products where the name matches your search query
    $sql = "SELECT p.*, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.name LIKE '%$search_query%'";
} else {
    // Default query if no search was performed
    $sql = "SELECT p.*, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id";
}

$result = $conn->query($sql);

// 3. Display a message if nothing matches the search
if ($result->num_rows == 0) {
    echo "<p class='no-results'>No products found matching your search.</p>";
}

// 4. Loop through and display the products
while($product = $result->fetch_assoc()):
?> 

<div class="product">
    
    <?php if(!empty($product['image'])): ?>
        <img src="assets/images/<?= $product['image']; ?>" alt="<?= $product['name']; ?>" class="product-image">
    <?php endif; ?>

    <h2>
        <a href="product_details.php?id=<?= $product['product_id']; ?>">
            <?= $product['name']; ?>
        </a>
    </h2>
    
    <p><?= $product['description']; ?></p>
    <p><strong>Category:</strong> <?= $product['category_name']; ?></p>
    <p><strong>Price:</strong> $<?= $product['price']; ?></p>

    <div class="product-actions" style="margin-top: 15px;">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="add_to_cart.php?id=<?= $product['product_id']; ?>" class="btn">Add to Cart</a>
        <?php else: ?>
            <button onclick="addToGuestCart(<?= $product['product_id']; ?>)" class="btn" style="cursor: pointer;">Add to Cart</button>
        <?php endif; ?>
    </div>
    </div>

<?php endwhile; ?>

</div> </div> <?php include 'includes/footer.php'; ?>

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

    // 3. Save the updated array back into browser storage memory
    localStorage.setItem('guest_cart', JSON.stringify(cart));
    
    alert('Item added to temporary guest cart!');
}
</script>

</body>
</html>