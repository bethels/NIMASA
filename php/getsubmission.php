<?php
header('Content-Type: application/json');

require 'database.php';

$id = $_POST['id'] ?? null;

if ($id) {
    // FIX: Use 'i' for integer since ID is a number
    $sql = "SELECT * FROM cabotage WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($data = $result->fetch_assoc()) {
        echo json_encode(["status" => "success", "data" => $data]);
    } else {
        echo json_encode(["status" => "error", "message" => "Submission not found"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "No ID provided"]);
}
$conn->close();
?>