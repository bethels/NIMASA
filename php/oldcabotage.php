<?php
session_start();
header('Content-Type: application/json');

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_SESSION['user_email'] ?? null;
    $tag = $_SESSION['last_application']['next_suggested_id'] ?? 0;

    if (!$mail) {
        echo json_encode(["status" => "error", "message" => "User not authenticated"]);
        exit;
    }

    // 1. Collect text inputs
    $text_data = [];
    for ($i = 1; $i <= 5; $i++) {
        $text_data[] = $_POST["dat$i"] ?? '';
    }

    // 2. Handle File Uploads with Session Fallback
    $file_keys = ['dat6','dat7','dat8','dat9','dat10','dat11','dat12','dat13','dat14','dat15','dat16','dat17','dat18']; 
    $file_paths = [];
    $original_names = [];
    $uploadFolder = "../uploads/";

    if (!is_dir($uploadFolder)) { mkdir($uploadFolder, 0777, true); }

    foreach ($file_keys as $index => $key) {
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
            $originalName = basename($_FILES[$key]["name"]); 
            $uniqueName = time() . "_" . $key . "_" . $originalName; 
            $tempPath = $uploadFolder . $uniqueName;
            if (move_uploaded_file($_FILES[$key]["tmp_name"], $tempPath)) {
                $savedPath = $tempPath; 
            }
        } else {
            // FALLBACK: Use existing session data if no new file uploaded
            $savedPath = $_SESSION['last_application']['form_fields']['file_server_paths'][$index] ?? "";
            $originalName = $_SESSION['last_application']['form_fields']['file_orig_names'][$index] ?? "";
        }
        $file_paths[] = $savedPath;
        $original_names[] = $originalName;
    }

    // 3. Logic Check: Completeness
    $isComplete = 1;
    foreach (array_merge($text_data, $file_paths) as $value) {
        if (empty($value) || trim($value) === "") { $isComplete = 0; break; }
    }

    // 4. Update Session state
    $_SESSION['last_application']['status'] = $isComplete;
    $_SESSION['last_application']['form_fields']['text_data'] = $text_data;
    $_SESSION['last_application']['form_fields']['file_server_paths'] = $file_paths;
    $_SESSION['last_application']['form_fields']['file_orig_names'] = $original_names;

    // 5. THE DUAL CHECK
    $checkSql = "SELECT id FROM cabotage WHERE email = ? AND tag = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("si", $mail, $tag);
    $checkStmt->execute();
    $exists = $checkStmt->get_result()->num_rows > 0;

    if ($exists) {
        // --- UPDATE ---
        $sql = "UPDATE cabotage SET 
                dat1=?, dat2=?, dat3=?, dat4=?, dat5=?, 
                dat6=?, dat7=?, dat8=?, dat9=?, dat10=?, dat11=?, dat12=?, dat13=?, dat14=?, dat15=?, dat16=?, dat17=?, dat18=?, 
                status=?, 
                dat6_name=?, dat7_name=?, dat8_name=?, dat9_name=?, dat10_name=?, dat11_name=?, dat12_name=?, dat13_name=?, dat14_name=?, dat15_name=?, dat16_name=?, dat17_name=?, dat18_name=?
                WHERE email = ? AND tag = ?";
        
        // Params: 18 strings (dat1-18), 1 int (status), 13 strings (names), 1 string (email), 1 int (tag) = 34
        $params = array_merge($text_data, $file_paths, [$isComplete], $original_names, [$mail, $tag]);
        $types = str_repeat("s", 18) . "i" . str_repeat("s", 13) . "si";
    } else {
        // --- INSERT ---
        $sql = "INSERT INTO cabotage (
                dat1, dat2, dat3, dat4, dat5, 
                dat6, dat7, dat8, dat9, dat10, dat11, dat12, dat13, dat14, dat15, dat16, dat17, dat18, 
                email, status, 
                dat6_name, dat7_name, dat8_name, dat9_name, dat10_name, dat11_name, dat12_name, dat13_name, dat14_name, dat15_name, dat16_name, dat17_name, dat18_name,
                tag
            ) VALUES (" . str_repeat("?,", 33) . "?)";
        
        // Params: 19 strings, 1 int, 13 strings, 1 int = 34
        $params = array_merge($text_data, $file_paths, [$mail, $isComplete], $original_names, [$tag]);
        $types = str_repeat("s", 19) . "i" . str_repeat("s", 13) . "i";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Data saved"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    }
}
$conn->close();
?>