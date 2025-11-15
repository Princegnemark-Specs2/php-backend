<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

include '../connect.php';

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Token validation
if (!$input || empty($input['token'])) {
    echo json_encode(["success" => false, "message" => "Token missing"]);
    exit;
}

$token = $conn->real_escape_string($input['token']);

// Check if token exists in admin table
$check = $conn->prepare("SELECT admin_id FROM admins WHERE token = ?");
$check->bind_param("s", $token);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid token"]);
    exit;
}

// Clear token (logout)
$logout = $conn->prepare("UPDATE admins SET token = NULL WHERE token = ?");
$logout->bind_param("s", $token);
$logout->execute();

// Response
if ($conn->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Logout successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Logout failed"]);
}

$conn->close();
?>
