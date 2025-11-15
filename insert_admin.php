<?php
include "../connect.php";

$name = "Admin Owner";
$email = "admin01@gmail.com";
$plainPassword = "admin123"; // change anytime

$hashed = password_hash($plainPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed);
$stmt->execute();

echo "Admin account created!";
?>
