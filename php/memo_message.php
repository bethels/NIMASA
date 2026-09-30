<?php
header('Content-Type: application/json');
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';
    $admin_key = "admin_admin";

    if (empty($message)) {
        echo json_encode(["status" => "error", "message" => "Message is empty"]);
        exit;
    }

    // 1. Check if "admin_admin" exists and GET the ID
    $checkSql = "SELECT id FROM msg WHERE mail = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $admin_key);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    $target_id = null;

    if ($row) {
        // 2. EXISTS: Update the existing record
        $target_id = $row['id']; // Store the existing ID
        $sql = "UPDATE msg SET msg = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $message, $target_id);
        $action = "updated";
    } else {
        // 3. DOES NOT EXIST: Insert a new record
        $sql = "INSERT INTO msg (mail, msg) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $admin_key, $message);
        $action = "inserted";
    }

    if ($stmt->execute()) {
        // Capture the ID if it was an INSERT
        if ($action === "inserted") {
            $target_id = $conn->insert_id;
        }

        // 4. Handle File Uploads
        $uploadDir = '../uploads/'; 
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $updatePairs = [];
        $uploadedCount = 0;

        for ($i = 1; $i <= 20; $i++) {
            if (isset($_FILES["file_$i"])) {
                $originalName = $_FILES["file_$i"]['name'];
                $tempPath = $_FILES["file_$i"]['tmp_name'];
                
                $safeFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $originalName);
                $targetPath = $uploadDir . $safeFileName;

                if (move_uploaded_file($tempPath, $targetPath)) {
                    $dbPath = "uploads/" . $safeFileName;
                    $updatePairs[] = "attach$i = '" . $conn->real_escape_string($dbPath) . "'";
                    $updatePairs[] = "attach{$i}_name = '" . $conn->real_escape_string($originalName) . "'";
                    $uploadedCount++;
                }
            }
        }

        // 5. Update the row with file paths using the correct $target_id
        if ($uploadedCount > 0 && $target_id) {
            $updateSql = "UPDATE msg SET " . implode(', ', $updatePairs) . " WHERE id = $target_id";
            $conn->query($updateSql);
        }

        echo json_encode(["status" => "success", "action" => $action, "files" => $uploadedCount]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
    $checkStmt->close();
}
$conn->close();
?>