<?php
header('Content-Type: application/json');

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);
    $index = intval($_POST['column_index']);

    // Map the index to your column names. 
    // If your columns are named 'dat6_status', 'dat7_status', etc:
    $colName = "dat" . $index . "_status";

    // Prepare the SQL. We use a whitelist for column names for security.
    $sql = "UPDATE cabotage SET $colName = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $status, $id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Prepare failed"]);
    }
}
$conn->close();
?>