<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json');

require 'database.php';

$email = $_SESSION['user_email'] ?? null;

if (!$email) {
    echo json_encode(["is_director" => false]);
    exit;
}


$stmt = $conn->prepare("SELECT file_path FROM nimdat WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($filePath);
$stmt->fetch();
$stmt->close();

$isDirector = ($filePath === "admin_admin_dir");

echo json_encode(["is_director" => $isDirector]);
$conn->close();
?>