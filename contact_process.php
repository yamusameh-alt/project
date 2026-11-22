<?php

include 'config/config.php';

$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$stmt = $conn->prepare(
"INSERT INTO contacts
(name, email, subject, message)
VALUES (?, ?, ?, ?)"
);

$stmt->bind_param(
"ssss",
$name,
$email,
$subject,
$message
);

$stmt->execute();

header("Location: contact.php?success=1");
exit();

?>