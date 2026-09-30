<?php
session_start();
header('Content-Type: application/json');
require 'database.php';

$slot = $_POST['slot'];
$path = $_POST['path'];
$tag = $_POST['tag'];
$email = $_SESSION['user_email'];

// 1. Delete physical file
if (file_exists($path)) {
    unlink($path);
}

// 2. Clear Database columns for this slot
$colPath = "pay" . $slot;
$colName = "pay" . $slot . "_name";

$sql = "UPDATE cabotage SET $colPath = NULL, $colName = NULL WHERE email = ? AND tag = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $email, $tag);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>