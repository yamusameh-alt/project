<?php

session_start();
include 'config/config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'];

// Check if product already exists in cart
$stmt = $conn->prepare(
"SELECT * FROM cart_items
 WHERE user_id=? AND product_id=?"
);

$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

    // Increase quantity
    $conn->query("
    UPDATE cart_items
    SET quantity = quantity + 1
    WHERE user_id=$user_id
    AND product_id=$product_id
    ");

}else{

    // Add new item
    $stmt = $conn->prepare(
    "INSERT INTO cart_items
    (user_id, product_id, quantity)
    VALUES (?, ?, 1)"
    );

    $stmt->bind_param(
    "ii",
    $user_id,
    $product_id
    );

    $stmt->execute();
}

header("Location: cart.php");
exit();

?>