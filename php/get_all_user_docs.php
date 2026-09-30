<?php
session_start();
// Turn on error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

require 'database.php';


$session_email = $_SESSION['user_email'] ?? '';

if (empty($session_email)) {
    echo json_encode(["status" => "error", "message" => "No email in session"]);
    exit;
}

// Query all applications for this email
$sql = "SELECT * FROM cabotage WHERE email = ? ORDER BY tag DESC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => $conn->error]);
    exit;
}

$stmt->bind_param("s", $session_email);
$stmt->execute();
$result = $stmt->get_result();

$all_applications = [];
while ($row = $result->fetch_assoc()) {
    $all_applications[] = $row;
}

echo json_encode($all_applications);
$stmt->close();
$conn->close();
?>