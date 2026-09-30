<?php
header('Content-Type: application/json');
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = floatval($_POST['status']);

    // Update the main 'status' column
    $sql = "UPDATE cabotage SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("di", $status, $id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        $stmt->close();
    }
}
$conn->close();
?>