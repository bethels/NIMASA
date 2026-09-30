<?php
header('Content-Type: application/json');

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
	$app_tag = $_POST['app_tag'] ?? '';

   
    $sql = "INSERT INTO msg (mail, msg, app_tag) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sss", $email, $message,$app_tag);
        if ($stmt->execute()) {
            $last_id = $conn->insert_id; 
            
            
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

           
            if ($uploadedCount > 0) {
                $updateSql = "UPDATE msg SET " . implode(', ', $updatePairs) . " WHERE id = $last_id";
                $conn->query($updateSql);
            }

            echo json_encode([
                "status" => "success", 
                "message" => "Message sent."
            ]);

        } else {
            echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Prepare failed"]);
    }
}
$conn->close();
?>