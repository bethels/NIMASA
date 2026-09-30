<?php
session_start();
header('Content-Type: application/json');

require 'database.php';


$tag = $_GET['tag'] ?? '';
$email = $_GET['email'] ?? '';

if (!$tag || !$email) {
    echo json_encode(["status" => "error", "message" => "Email or Tag missing in request"]);
    exit;
}

$sql = "SELECT pay1, pay2, pay3, pay4, pay5, pay6, pay7, pay8, pay9, pay10, 
               pay1_name, pay2_name, pay3_name, pay4_name, pay5_name, 
               pay6_name, pay7_name, pay8_name, pay9_name, pay10_name 
        FROM cabotage 
        WHERE email = ? AND tag = ? 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $tag);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

if ($record) {
    echo json_encode(["status" => "success", "record" => $record]);
} else {
    // Adding specific debug info to the error message
    echo json_encode(["status" => "error", "message" => "No record found for $email with tag $tag"]);
}

$stmt->close();
$conn->close();
?>