<?php
include 'config/config.php';

$message = "";

if(isset($_POST['register'])){

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if($password != $confirm_password){

        $message = "Passwords do not match!";

    }else{

        $check = $conn->prepare(
            "SELECT user_id FROM users WHERE email=?"
        );

        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){

            $message = "Email already exists!";

        }else{

            $hashed_password =
            password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users
                (full_name,email,password,phone,address)
                VALUES (?,?,?,?,?)"
            );

            $stmt->bind_param(
                "sssss",
                $full_name,
                $email,
                $hashed_password,
                $phone,
                $address
            );

            if($stmt->execute()){

                $message = "Registration Successful!";

            }else{

                $message = "Registration Failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">

    <form method="POST">

        <h1>Create Account</h1>

        <?php if(!empty($message)): ?>
            <p><?= $message; ?></p>
        <?php endif; ?>

        <input
            type="text"
            name="full_name"
            placeholder="Full Name"
            required>

        <input
            type="email"
            name="email"
            placeholder="Email"
            required>

        <input
            type="password"
            name="password"
            placeholder="Password"
            required>

        <input
            type="password"
            name="confirm_password"
            placeholder="Confirm Password"
            required>

        <input
            type="text"
            name="phone"
            placeholder="Phone">

        <textarea
            name="address"
            placeholder="Address"></textarea>

        <button
            type="submit"
            name="register">
            Register
        </button>

    </form>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>