<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json');

// Prevents accidental warnings from breaking JSON response
error_reporting(0); 
require '../php/check_session.php'; 

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_SESSION['user_email'] ?? null;
    $tag = $_POST['tag'] ?? 0;
    $uploadFolder = "../uploads/";

    if (!$mail) {
        echo json_encode(["status1" => "error", "message" => "Session expired"]);
        exit;
    }

    // --- STEP 1: PRE-FETCH CURRENT STATE ---
    $currentData = [];
    $checkStmt = $conn->prepare("SELECT * FROM cabotage WHERE email = ? AND tag = ?");
    $checkStmt->bind_param("si", $mail, $tag);
    $checkStmt->execute();
    $res = $checkStmt->get_result();
    $exists = $res->num_rows > 0;
    if ($exists) { $currentData = $res->fetch_assoc(); }
    $checkStmt->close();

    // Define the full range for the "Safe Delete" check
    $all_cols = [];
    for ($i = 6; $i <= 34; $i++) { $all_cols[] = "dat$i"; }

    // --- STEP 2: PROCESS dat19 TO dat34 ---
    $update_values = [];
    $types = "";
    $params = [];

    // Loop through the fields sent by your JS loop (19 to 33)
    for ($i = 12; $i <= 33; $i++) {
        $updateFlag = $_POST["update_flag_$i"] ?? 0;
        
        $col_path = "dat$i";
        $col_name = "dat{$i}_name";
        $col_stat = "dat{$i}_status";

        if ($updateFlag == 99) {
            $oldPath = $currentData[$col_path] ?? "";

            // A. SAFE DELETE OLD FILE
            if (!empty($oldPath) && file_exists($oldPath)) {
                $usageCount = 0;
                foreach ($all_cols as $col) {
                    $uStmt = $conn->prepare("SELECT COUNT(*) as total FROM cabotage WHERE $col = ? AND (email != ? OR tag != ?)");
                    $uStmt->bind_param("ssi", $oldPath, $mail, $tag);
                    $uStmt->execute();
                    $usageCount += $uStmt->get_result()->fetch_assoc()['total'];
                    $uStmt->close();
                }
                if ($usageCount == 0) { @unlink($oldPath); }
            }

            // B. HANDLE NEW FILE OR CLEARING
            if (isset($_FILES[$col_path]) && $_FILES[$col_path]['error'] === UPLOAD_ERR_OK) {
                $uniqueName = time() . "_$col_path" . "_" . basename($_FILES[$col_path]["name"]);
                $target = $uploadFolder . $uniqueName;
                if (move_uploaded_file($_FILES[$col_path]["tmp_name"], $target)) {
                    $update_values[$col_path] = $target;
                    $update_values[$col_name] = $_POST[$col_name] ?? "";
                    $update_values[$col_stat] = 0;
                }
            } else {
                $update_values[$col_path] = ""; // Replace with NULL/Empty
                $update_values[$col_name] = "";
                $update_values[$col_stat] = 0;
            }
        } else {
            // Keep existing data
            $update_values[$col_path] = $currentData[$col_path] ?? "";
            $update_values[$col_name] = $currentData[$col_name] ?? "";
            $update_values[$col_stat] = $currentData[$col_stat] ?? 0;
        }
    }

    // --- STEP 3: CONSTRUCT DYNAMIC UPDATE ---
    // We only update if the record exists (Cabotage 2 assumes step 1 created it)
    if ($exists) {
        $set_parts = [];
        foreach ($update_values as $col => $val) {
            $set_parts[] = "$col = ?";
            $params[] = $val;
            $types .= (strpos($col, 'status') !== false) ? "i" : "s";
        }

        // Add email and tag to parameters for the WHERE clause
        $sql = "UPDATE cabotage SET " . implode(", ", $set_parts) . " WHERE email = ? AND tag = ?";
        $params[] = $mail;
        $params[] = $tag;
        $types .= "si";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
			
            echo json_encode(["status1" => "success", "message" => "Final files updated"]);
        } else {
            echo json_encode(["status1" => "error", "message" => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status1" => "error", "message" => "Initial record not found. Save page 1 first."]);
    }
}
$conn->close();
?>