<?php
session_start();
include 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_placed = false;
$order_id = 0;
$total_amount = 0;

// Handle the actual order placement when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    
    // 1. Fetch items using a safe prepared statement
    $sql = "SELECT c.product_id, c.quantity, p.price 
            FROM cart_items c 
            JOIN products p ON c.product_id = p.product_id 
            WHERE c.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $total_amount += $row['price'] * $row['quantity'];
            $items[] = $row;
        }

        // 2. Insert into orders table
        $shipping_address = "Customer Address"; // Replace with actual form data if needed
        $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_address) VALUES (?, ?, ?)");
        $stmt_order->bind_param("ids", $user_id, $total_amount, $shipping_address);
        $stmt_order->execute();
        $order_id = $conn->insert_id;

        // 3. Insert into order_items table
        foreach ($items as $item) {
            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
            $stmt_item->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt_item->execute();
        }

        // 4. Empty the cart
        $stmt_delete = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $stmt_delete->bind_param("i", $user_id);
        $stmt_delete->execute();

        $order_placed = true;
    }
} else {
    // If not a POST request, just calculate total for presentation
    $sql = "SELECT c.quantity, p.price 
            FROM cart_items c 
            JOIN products p ON c.product_id = p.product_id 
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $total_amount += $row['price'] * $row['quantity'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container" style="padding: 40px; max-width: 600px; margin: 0 auto;">

    <?php if ($order_placed): ?>
        <!-- Success State -->
        <h1 style="color: green;">Order Placed Successfully!</h1>
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order_id); ?></p>
        <p><strong>Total Amount:</strong> $<?= htmlspecialchars($total_amount); ?></p>
        <br>
        <a href="products.php" class="btn">Continue Shopping</a>

    <?php else: ?>
        <!-- Checkout Form State -->
        <h1>Checkout Summary</h1>
        <p><strong>Your Current Total:</strong> $<?= htmlspecialchars($total_amount); ?></p>
        
        <?php if ($total_amount > 0): ?>
            <form method="POST" action="checkout.php">
                <!-- Click this button to run the PHP insertion securely -->
                <button type="submit" name="place_order" style="padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer;">
                    Confirm & Place Order
                </button>
            </form>
        <?php else: ?>
            <p style="color: red;">Your cart is empty. Please add items before checking out.</p>
            <a href="products.php">Browse Products</a>
        <?php endif; ?>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
