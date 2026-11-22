<?php

session_start();
include 'config/config.php';

$message = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email=?"
    );

    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()){

        if(password_verify(
            $password,
            $user['password']
        )){

            $_SESSION['user_id'] =
            $user['user_id'];

            $_SESSION['full_name'] =
            $user['full_name'];

            $_SESSION['role'] =
            $user['role'];

            // --- LOCALSTORAGE CART MERGE SEQUENCE ---
            
            echo "
            <script>
                let guestCart = localStorage.getItem('guest_cart');
                
                if (guestCart && JSON.parse(guestCart).length > 0) {
                    // Send guest data to your merge_cart.php background script
                    fetch('merge_cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: guestCart
                    })
                    .then(() => {
                        localStorage.removeItem('guest_cart'); // Clear the guest cart out of memory
                        window.location.href = 'profile.php';  // Proceed to profile page
                    });
                } else {
                    window.location.href = 'profile.php';      // No items to merge, go straight to profile
                }
            </script>
            ";
            exit();
            // ----------------------------------------

        }else{
            $message = "Wrong password!";
        }

    }else{
        $message = "Email not found!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="assets/css/style.css?v=2">
</head>

<body>

<?php include 'includes/navbar.php'; ?>
<div class="container">
<h1>Login</h1>

<p><?php echo $message; ?></p>

<form method="POST">

<input
type="email"
name="email"
placeholder="Email"
required>

<br><br>

<input
type="password"
name="password"
placeholder="Password"
required>

<br><br>

<button
type="submit"
name="login">
Login
</button>

</form>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
