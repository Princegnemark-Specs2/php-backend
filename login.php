<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "Missing credentials"]);
    exit;
}

$email = trim($data['email']);
$password = trim($data['password']);

// Check if user exists
$stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Email not found"]);
    exit;
}

$user = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $user['password'])) {
    echo json_encode(["success" => false, "message" => "Incorrect password"]);
    exit;
}

// Generate token
$token = bin2hex(random_bytes(16));

// Update token in DB
$update = $conn->prepare("UPDATE customers SET token=? WHERE customer_id=?");
$update->bind_param("si", $token, $user['customer_id']);
$update->execute();

echo json_encode([
    "success" => true,
    "message" => "Login successful!",
    "user" => [
        "customer_id" => $user['customer_id'],
        "name" => $user['name'],
        "email" => $user['email'],
        "token" => $token
    ]
]);

$stmt->close();
$update->close();
$conn->close();
?>
