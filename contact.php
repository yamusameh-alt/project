<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">

    <h1>Contact Us</h1>

    <?php
    if(isset($_GET['success'])){
        echo "<p style='color:green;'>Message sent successfully!</p>";
    }
    ?>

    <form action="contact_process.php" method="POST">

        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Subject:</label><br>
        <input type="text" name="subject"><br><br>

        <label>Message:</label><br>
        <textarea name="message" rows="6" cols="50" required></textarea><br><br>

        <button type="submit">Send Message</button>

    </form>

    <hr>

</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>