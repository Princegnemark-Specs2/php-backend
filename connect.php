<?php
$host = "127.0.0.1";
$user = "root"; // your XAMPP username
$pass = ""; // your MySQL password if any
$dbname = "catering_data";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}
?>
