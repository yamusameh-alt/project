<?php
session_start();
include 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$update_success = "";
$update_error = "";

// 1. HANDLE PROFILE UPDATE FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (empty($full_name) || empty($email)) {
        $update_error = "Name and Email fields are required.";
    } else {
        $update_sql = "UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("ssssi", $full_name, $email, $phone, $address, $user_id);

        if ($stmt_update->execute()) {
            $update_success = "Profile updated successfully!";
            $_SESSION['full_name'] = $full_name;
        } else {
            $update_error = "Something went wrong. Please try again.";
        }
    }
}

// 2. FETCH CURRENT USER INFORMATION FROM DATABASE
$user_sql = "SELECT full_name, email, phone, address, role FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($user_sql);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result(); // Fixed typo here
$user = $user_result->fetch_assoc();

// 3. FETCH USER'S ORDER HISTORY
$order_sql = "SELECT order_id, order_date, total_amount, shipping_address FROM orders WHERE user_id = ? ORDER BY order_id DESC";
$stmt_orders = $conn->prepare($order_sql);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$order_result = $stmt_orders->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile & Order History</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <style>
        .profile-layout { display: flex; gap: 40px; margin-top: 20px; }
        .profile-section { flex: 1; background: #fdfdfd; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .orders-section { flex: 1.5; background: #fdfdfd; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .history-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .history-table th, .history-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .history-table th { background-color: #f5f5f5; }
        .alert-success { color: green; font-weight: bold; margin-bottom: 15px; }
        .alert-error { color: red; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container" style="padding: 20px; max-width: 1200px; margin: 0 auto;">

    <h1>Welcome, <?= htmlspecialchars($user['full_name'] ?? ''); ?>!</h1>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['role'] ?? ''); ?></p>
    <a href="logout.php"><button style="padding: 5px 15px; cursor: pointer;">Logout</button></a>

    <?php if (!empty($update_success)): ?>
        <p class="alert-success"><?= $update_success; ?></p>
    <?php endif; ?>
    <?php if (!empty($update_error)): ?>
        <p class="alert-error"><?= $update_error; ?></p>
    <?php endif; ?>

    <div class="profile-layout">
        
        <!-- UPDATE PROFILE FORM -->
        <div class="profile-section">
            <h2>Update Information</h2>
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Shipping Address</label>
                    <textarea id="address" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" name="update_profile" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- ORDER HISTORY SECTION -->
        <div class="orders-section">
            <h2>Your Order History</h2>
            <?php if ($order_result->num_rows > 0): ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date Ordered</th>
                            <th>Total Spent</th>
                            <th>Address Sent To</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $order_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($order['order_id']); ?></td>
                                <td><?= htmlspecialchars($order['order_date'] ?? 'N/A'); ?></td>
                                <td>$<?= htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                                <td><?= htmlspecialchars($order['shipping_address']); ?></td>
                            </tr>
                        <?php endwhile; ?> <!-- Fixed syntax discrepancy here -->
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #666; margin-top: 15px;">You haven't placed any orders yet.</p>
            <?php endif; ?>
        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
