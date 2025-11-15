<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

// Check token
if (!$input || empty($input['token'])) {
    echo json_encode(["success" => false, "message" => "Token missing"]);
    exit;
}

$token = $conn->real_escape_string($input['token']);

// Clear token
$conn->query("UPDATE customers SET token=NULL WHERE token='$token'");

if ($conn->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Logout successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid token"]);
}

$conn->close();
?>
