<?php
session_start();
include 'config/config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. HANDLE QUANTITY UPDATE SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $product_id = intval($_POST['product_id']);
    $new_quantity = intval($_POST['quantity']);

    if ($new_quantity > 0) {
        
        $update_sql = "UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        
        $delete_sql = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
    
    // Refresh to update values and totals instantly
    header("Location: cart.php");
    exit();
}


$sql = "
SELECT
p.product_id,
p.name,
p.price,
c.quantity,
(p.price * c.quantity) AS total
FROM cart_items c
JOIN products p
ON c.product_id = p.product_id
WHERE c.user_id = ?
";

$stmt_fetch = $conn->prepare($sql);
$stmt_fetch->bind_param("i", $user_id);
$stmt_fetch->execute();
$result = $stmt_fetch->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Shopping Cart</title>
<link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container" style="padding: 20px; max-width: 1000px; margin: 0 auto;">

<h1>My Cart</h1>

<?php if ($result->num_rows > 0): ?>
<table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
<tr>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Total</th>
<th>Action</th>
</tr>

<?php
$grand_total = 0;
while($row = $result->fetch_assoc()):
$grand_total += $row['total'];
?>
<tr>
<td><?= htmlspecialchars($row['name']); ?></td>
<td>$<?= htmlspecialchars(number_format($row['price'], 2)); ?></td>
<td>
  
    <form method="POST" action="cart.php" style="display: inline-flex; gap: 5px; align-items: center;">
        <input type="hidden" name="product_id" value="<?= $row['product_id']; ?>">
        <input type="number" name="quantity" value="<?= $row['quantity']; ?>" min="1" max="99" style="width: 60px; padding: 4px; text-align: center;">
        <button type="submit" name="update_quantity" style="padding: 4px 8px; font-size: 12px; cursor: pointer;">Update</button>
    </form>
</td>
<td>$<?= htmlspecialchars(number_format($row['total'], 2)); ?></td>
<td>
<a href="remove_from_cart.php?id=<?= $row['product_id']; ?>" style="color: red;" onclick="return confirm('Remove item from cart?');">
Remove
</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<h2>Grand Total: $<?= htmlspecialchars(number_format($grand_total, 2)); ?></h2>
<br>
<a href="checkout.php">
    <button style="padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; border-radius: 4px; font-size: 16px;">Proceed to Checkout</button>
</a>

<?php else: ?>
    <p>Your cart is empty.</p>
    <a href="products.php">Browse Products</a>
<?php endif; ?>

</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
