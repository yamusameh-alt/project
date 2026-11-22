<?php
session_start();
include 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];
$json_data = file_get_contents('php://input');
$cart_items = json_decode($json_data, true);

if (!empty($cart_items)) {
    foreach ($cart_items as $item) {
        $product_id = intval($item['product_id']);
        $quantity_to_add = intval($item['quantity']);

        // Check if the item is already in the database cart for this user
        $stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + $quantity_to_add;

            $update_stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
            $update_stmt->execute();
        } else {
            $insert_stmt = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity_to_add);
            $insert_stmt->execute();
        }
    }
    echo "Success";
}
?>